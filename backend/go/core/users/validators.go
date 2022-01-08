package users

import (
	"mime/multipart"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
)

const (
	User int = iota + 1
	Employee
	Admin
)

type UserModelValidator struct {
	User struct {
		Username string                `form:"username" json:"username" binding:"required,alphanum,min=4,max=255"`
		Email    string                `form:"email" json:"email" binding:"required,email"`
		Password string                `form:"password" json:"password" binding:"required,min=8,max=255"`
		Image    *multipart.FileHeader `form:"image" json:"image" binding:"omitempty"`
		Role     uint8                 `form:"role" json:"role" binding:"gte=1,lte=3"`
		Verify   bool                  `form:"verify" json:"verify" default:"false"`
	} `json:"user"`
	userModel       UserModel `json:"-"`
	oldPasswordHash string    `json:"-"`
}

func (userMV *UserModelValidator) Bind(c *gin.Context) error {
	err := utils.Bind(c, userMV)
	if err != nil {
		return err
	}

	if userMV.User.Role == 0 {
		userMV.User.Role = 1
	}

	userMV.userModel.Username = userMV.User.Username
	userMV.userModel.Email = userMV.User.Email
	userMV.userModel.Role = userMV.User.Role
	userMV.userModel.Verify = userMV.User.Verify

	if userMV.User.Password != userMV.oldPasswordHash {
		userMV.userModel.setPassword(userMV.User.Password)
	}

	return nil
}

// You can put the default value of a Validator here
func NewUserModelValidator() UserModelValidator {
	userModelValidator := UserModelValidator{}
	//userModelValidator.User.Email ="w@g.cn"
	return userModelValidator
}

func NewUserModelValidatorFillWith(userModel UserModel) UserModelValidator {
	userModelValidator := NewUserModelValidator()
	userModelValidator.User.Username = userModel.Username
	userModelValidator.User.Email = userModel.Email
	userModelValidator.User.Role = userModel.Role
	userModelValidator.User.Verify = userModel.Verify
	userModelValidator.User.Password = userModel.PasswordHash
	userModelValidator.oldPasswordHash = userModel.PasswordHash

	userModelValidator.User.Image = nil

	return userModelValidator
}

package users

import (
	"encoding/json"
	"fmt"
	"net/http"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
	"github.com/golang-jwt/jwt"
)

func handleError(c *gin.Context, err error) {
	switch err {
	case http.ErrNoCookie:
		c.JSON(403, gin.H{
			"error": "no-session",
		})

	default:
		c.JSON(500, gin.H{
			"error": err,
		})
	}
}

func UpgradeTokenToAdmin(c *gin.Context) {
	var err error
	var token *jwt.Token
	session, err := c.Cookie("session")

	if err != nil {
		handleError(c, err)
		return
	}

	token, err = jwt.Parse(session, func(t *jwt.Token) (interface{}, error) {
		if _, ok := t.Method.(*jwt.SigningMethodRSA); !ok {
			return nil, fmt.Errorf("unexpected signing method: %v", t.Header["alg"])
		}
		return utils.GetJwtMainPrivateKey(), nil
	})

	if err != nil {
		handleError(c, err)
		return
	}

	fmt.Printf("token: %v\n", token)
}

func UsersCreation(c *gin.Context) {
	userModelValidator := NewUserModelValidator()

	if err := userModelValidator.Bind(c); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewValidatorError(err))
		return
	}

	if err := SaveOne(&userModelValidator.userModel); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewError("database", err))
		return
	}

	c.JSON(http.StatusCreated, gin.H{"user": Serialize(userModelValidator.userModel)})
}

func UserUpdate(c *gin.Context) {
	myUserModel, err := FindOneUser(UserModel{ID: uuid.FromStringOrNil(c.Param("uuid"))})
	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}

	userModelValidator := NewUserModelValidatorFillWith(myUserModel)
	if err := userModelValidator.Bind(c); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewValidatorError(err))
		return
	}

	userModelValidator.userModel.ID = myUserModel.ID
	if err := myUserModel.Update(userModelValidator.userModel); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewError("database", err))
		return
	}

	c.JSON(http.StatusOK, gin.H{"user": Serialize(myUserModel)})
}

func UserList(c *gin.Context) {
	myUserModels, err := FindAllUsers(c)
	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}

	c.JSON(http.StatusOK, gin.H{"users": MultipleSerialize(myUserModels)})
}

func UserDelete(c *gin.Context) {
	data, err := utils.ParseJSON(c)
	var uuids struct {
		Uuids []uuid.UUID
	}
	json.Unmarshal(data, &uuids)

	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}

	DeleteUsers(uuids.Uuids)
}

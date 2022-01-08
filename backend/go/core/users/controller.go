package users

import (
	"encoding/json"
	"net/http"
	"os"
	"strconv"
	"time"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
)

func UsersCreation(c *gin.Context) {
	userModelValidator := NewUserModelValidator()

	if err := userModelValidator.Bind(c); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewValidatorError(err))
		return
	}

	img := userModelValidator.User.Image
	if img != nil {
		if !utils.IsAllowedImageType(img.Header) {
			c.JSON(http.StatusBadRequest, utils.NewError("wrongimageformat", nil))
			return
		}
	}

	if err := SaveOne(&userModelValidator.userModel); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewError("database", err))
		return
	}

	imagePath := userModelValidator.userModel.ID.String()
	if img != nil {
		userModelValidator.userModel.Image = &imagePath

		err := c.SaveUploadedFile(img, "/app_data/img/users/"+imagePath)
		if err != nil {
			c.JSON(http.StatusInternalServerError, utils.NewError("imagesavingfailed", err))
			return
		}

		if err := SaveOne(&userModelValidator.userModel); err != nil {
			c.JSON(http.StatusUnprocessableEntity, utils.NewError("database", err))
			return
		}
	}

	user, err := FindOneUser(UserModel{ID: userModelValidator.userModel.ID})
	if err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewError("usernotset", err))
		return
	}

	c.JSON(http.StatusCreated, gin.H{"user": Serialize(user)})
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

	img := userModelValidator.User.Image
	imgName := myUserModel.ID.String()
	if img != nil {
		if !utils.IsAllowedImageType(img.Header) {
			c.JSON(http.StatusBadRequest, utils.NewError("wrongimageformat", nil))
			return
		}

		imgUrl := imgName + "?v=" + strconv.FormatInt(time.Now().Unix(), 10)
		userModelValidator.userModel.Image = &imgUrl

		err = c.SaveUploadedFile(img, "/app_data/img/users/"+imgName)
		if err != nil {
			c.JSON(http.StatusInternalServerError, utils.NewError("imagesavingfailed", err))
			return
		}
	}

	userModelValidator.userModel.ID = myUserModel.ID
	if err := myUserModel.Update(userModelValidator.userModel); err != nil {
		c.JSON(http.StatusUnprocessableEntity, utils.NewError("database", err))
		return
	}

	c.JSON(http.StatusOK, gin.H{"user": Serialize(myUserModel)})
}

func UserList(c *gin.Context) {
	myUserModels, dataPager, err := FindAllUsersPag(c)
	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}
	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}
	c.JSON(http.StatusOK, gin.H{"users": MultipleSerialize(myUserModels), "pager": Pager(dataPager)})
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

	for _, id := range uuids.Uuids {
		os.Remove("/app_data/img/users/" + id.String())
	}

	DeleteUsers(uuids.Uuids)
}

func UserVerify(c *gin.Context) {
	data, err := utils.ParseJSON(c)
	var uuids struct {
		Uuids []uuid.UUID
	}
	json.Unmarshal(data, &uuids)

	if err != nil {
		c.Status(http.StatusNotFound)
		return
	}

	VerifyUsers(uuids.Uuids)
}

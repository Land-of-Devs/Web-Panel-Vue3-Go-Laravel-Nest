package users

import (
	"fmt"
	"net/http"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
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

type Tag struct {
	Name        string `json:"name" form:"name" binding:"required"`
	Description string `json:"description" form:"description" binding:"required"`
	ExtraData   string `json:"extra"`
}

func Users(c *gin.Context) {
	users := []Tag{{Name: "a", Description: ""}}

	c.JSON(200, gin.H{
		"users": users,
	})
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

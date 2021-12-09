package auth

import (
	"fmt"
	"time"
	"webpanel/core/users"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
)

func SetTestToken(c *gin.Context) {
	testUuid, err := uuid.NewV4()

	if err != nil {
		panic(err)
	}

	tokstr, err := utils.CreateMainToken(utils.SessionTokenData{
		UserId: testUuid,
	})

	if err != nil {
		panic(err)
	}

	fmt.Printf("tokstr: %v, uuid: %v\n", tokstr, testUuid)

	SetSession(c, tokstr)

	c.JSON(200, gin.H{
		"ok": true,
	})
}

func UpgradeTokenToAdmin(c *gin.Context) {
	user := c.MustGet("user").(users.UserModel)
	session := c.MustGet("session").(utils.SessionTokenData)

	if user.Role != Admin {
		c.Status(403)
		return
	}

	/* verificar codigo 2fa aqui */
	ok := true

	if !ok {
		c.Status(403)
		return
	}

	session.AdminAccessToken = time.Now().Unix() + 60*30 /* 30 min */
	newTok, err := utils.CreateMainToken(session)

	if err != nil {
		c.JSON(500, gin.H{
			"error": err.Error(),
		})
		return
	}

	SetSession(c, newTok)
}

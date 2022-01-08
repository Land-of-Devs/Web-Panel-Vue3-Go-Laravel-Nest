package auth

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
	"time"
	"webpanel/core/users"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/golang-jwt/jwt"
)

func GetSessionInfo(c *gin.Context) {
	user := c.MustGet("user").(users.UserModel)
	session := *c.MustGet("session").(*utils.SessionTokenData)

	c.IndentedJSON(200, gin.H{
		"user":    user,
		"session": session,
	})
}

func SetTestToken(c *gin.Context) {
	usr, err := users.FindOneUser(users.UserModel{Username: "admin"})

	if err != nil {
		c.JSON(500, gin.H{
			"error": err.Error(),
		})
		return
	}

	admaccess := time.Now().Unix() + 60*30
	tokstr, err := utils.CreateMainToken(utils.SessionTokenData{
		StandardClaims:   jwt.StandardClaims{Subject: usr.ID.String()},
		AdminAccessToken: admaccess,
	})

	if err != nil {
		panic(err)
	}

	fmt.Printf("tokstr: %v, uuid: %v\n", tokstr, usr.ID)

	SetSession(c, tokstr, &usr, admaccess)

	c.JSON(200, gin.H{
		"ok": true,
	})
}

func UpgradeTokenToAdmin(c *gin.Context) {
	user := c.MustGet("user").(users.UserModel)
	session := *c.MustGet("session").(*utils.SessionTokenData)

	if user.Role != Admin {
		c.Status(403)
		return
	}

	tsv := NewTwoStepValidator()
	/* TODO: change to tsv.Bind(c) when endpoint method changes to POST */
	if err := c.ShouldBindQuery(&tsv); err != nil {
		c.Status(400)
		return
	}

	fmt.Printf("tsv: %v\n", tsv)
	postBody, _ := json.Marshal(map[string]string{
		"uuid": user.ID.String(),
		"code": tsv.Code,
	})

	responseBody := bytes.NewBuffer(postBody)
	resp, err := http.Post("http://wp_laravel:3000/api/internal/twostep/validate", "application/json", responseBody)

	if err != nil {
		fmt.Printf("error checking twostep code: %v\n", err)
		c.Status(503)
		return
	}

	defer resp.Body.Close()

	ok := resp.StatusCode == 200

	if !ok {
		if gin.Mode() == gin.DebugMode {
			body, _ := ioutil.ReadAll(resp.Body)

			c.Data(resp.StatusCode, "text/html; charset=utf-8", body)
		} else {
			c.Status(401)
		}

		return
	}

	session.AdminAccessToken = time.Now().Unix() + 60*30 /* 30 min */
	newTok, err := utils.CreateMainToken(session)

	if err != nil {
		fmt.Printf("error creating main token on upgrade: %v\n", err)
		c.Status(500)
		return
	}

	SetSession(c, newTok, &user, session.AdminAccessToken)
	c.JSON(200, gin.H{
		"ok": true,
	})
}

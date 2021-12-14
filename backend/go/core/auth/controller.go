package auth

import (
	"bytes"
	"encoding/json"
	"fmt"
	"net/http"
	"time"
	"webpanel/core/users"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
	"github.com/golang-jwt/jwt"
)

func SetTestToken(c *gin.Context) {
	testUuid, err := uuid.NewV4()

	if err != nil {
		panic(err)
	}

	tokstr, err := utils.CreateMainToken(utils.SessionTokenData{
		StandardClaims: jwt.StandardClaims{Subject: testUuid.String()},
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

	tsv := NewTwoStepValidator()
	if err := tsv.Bind(c); err != nil {
		c.Status(400)
		return
	}

	postBody, _ := json.Marshal(map[string]string{
		"uuid": user.ID.String(),
		"code": tsv.Code,
	})

	responseBody := bytes.NewBuffer(postBody)
	resp, err := http.Post("http://wp_laravel:3000/api/internal/twostep", "application/json", responseBody)

	if err != nil {
		fmt.Printf("error checking twostep code: %v\n", err)
		c.Status(500)
		return
	}

	defer resp.Body.Close()

	// body, err := ioutil.ReadAll(resp.Body)

	// if err != nil {
	// 	fmt.Errorf("error checking twostep code resp: %v", err)
	// 	c.Status(500)
	// 	return
	// }

	ok := resp.StatusCode == 200

	if !ok {
		c.Status(403)
		return
	}

	session.AdminAccessToken = time.Now().Unix() + 60*30 /* 30 min */
	newTok, err := utils.CreateMainToken(session)

	if err != nil {
		fmt.Printf("error creating main token on upgrade: %v\n", err)
		c.Status(500)
		return
	}

	SetSession(c, newTok)
	c.JSON(200, gin.H{
		"ok": true,
	})
}

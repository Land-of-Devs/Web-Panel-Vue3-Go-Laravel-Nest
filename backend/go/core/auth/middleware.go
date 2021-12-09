package auth

import (
	"fmt"
	"net/http"
	"time"
	"webpanel/core/users"
	"webpanel/db"
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
			"error": err.Error(),
		})
	}
}

func SetSession(c *gin.Context, tokstr string) {
	var maxAge int = 60 * 60 * 24 * 2
	if len(tokstr) == 0 {
		maxAge = -1
	}

	c.SetCookie("session", tokstr, maxAge, "/", "", false, true)
}

func handleTokenError(optional bool, token *jwt.Token, c *gin.Context, err error, deleteToken bool) {
	if token != nil && deleteToken {
		SetSession(c, "")
	}

	if !optional {
		handleError(c, err)
		c.Abort()
	} else {
		c.Next()
	}
}

func ReadSessionEx(optional bool, roleRequired uint8, mustBeUpgraded bool) func(*gin.Context) {
	return func(c *gin.Context) {
		var err error
		var token *jwt.Token
		var data *utils.SessionTokenData
		var ok bool

		session, err := c.Cookie("session")

		if err != nil {
			handleTokenError(optional, token, c, err, true)
			return
		}

		token, err = utils.ParseMainToken(session)

		if err != nil {
			handleTokenError(optional, token, c, err, true)
			return
		}

		data, ok = token.Claims.(*utils.SessionTokenData)
		if !ok || !token.Valid {
			handleTokenError(optional, token, c, jwt.ValidationError{
				Errors: jwt.ValidationErrorClaimsInvalid,
			}, true)
			return
		}

		now := time.Now().Unix()
		hasAdminUpgrade := now < data.AdminAccessToken
		hasStaffUpgrade := now < data.StaffAccessToken

		/* Get the user of this token from the DB */
		db := db.GetConnection()
		user := users.UserModel{ID: data.UserId}
		db.Where(&user).First(&user)

		if user.ID.IsNil() {
			handleTokenError(optional, token, c, jwt.ValidationError{
				Errors: jwt.ValidationErrorClaimsInvalid,
			}, true)
			return
		}

		fmt.Printf("user: %v, data: %v\n", user, data)

		if user.Role < roleRequired ||
			(mustBeUpgraded &&
				((roleRequired == Admin && !hasAdminUpgrade) ||
					(roleRequired == Staff && !hasStaffUpgrade))) {
			handleTokenError(optional, token, c, &utils.ErrForbidden{}, false)
			return
		}

		c.Set("session", data)
		c.Set("user", user)
		c.Next()
	}
}

func ReadSessionAdmin() func(*gin.Context) {
	return ReadSessionEx(false, Admin, true)
}

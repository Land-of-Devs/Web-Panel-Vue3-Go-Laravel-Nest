package auth

import (
	"encoding/json"
	"fmt"
	"net/http"
	"strconv"
	"time"
	"webpanel/core/users"
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
			"error": err.Error(),
		})
	}
}

func SetSession(c *gin.Context, tokstr string, user *users.UserModel, adminAccess int64) {
	var maxAge int = 60 * 60 * 24 * 2
	var maxAgeUserData int = maxAge
	var maxAgeAdminAccess int = (int)(adminAccess - time.Now().Unix())

	var userDataStr string = ""
	var adminAccessStr string = strconv.FormatInt(adminAccess, 10)

	if len(tokstr) == 0 {
		maxAge = -1
		maxAgeUserData = -1
		maxAgeAdminAccess = -1
		adminAccessStr = ""
	}

	if user == nil || user.Role != Admin || maxAgeAdminAccess <= 0 {
		maxAgeAdminAccess = -1
		adminAccessStr = ""
	}

	if maxAgeUserData > 0 && user != nil {
		if busr, err := json.Marshal(users.Serialize(*user)); err == nil {
			userDataStr = string(busr)
		}
	}

	c.SetSameSite(http.SameSiteStrictMode)
	c.SetCookie("session", tokstr, maxAge, "/", "", false, true)
	c.SetCookie("userdata", userDataStr, maxAgeUserData, "/", "", false, false)
	c.SetCookie("adminaccess", adminAccessStr, maxAgeAdminAccess, "/", "", false, false)
}

func handleTokenError(optional bool, token *jwt.Token, c *gin.Context, err error, deleteToken bool) {
	if token != nil && deleteToken {
		SetSession(c, "", nil, 0)
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

		usrUuid, err := uuid.FromString(data.Subject)

		if err != nil {
			handleTokenError(optional, token, c, jwt.ValidationError{
				Errors: jwt.ValidationErrorClaimsInvalid,
			}, true)
			return
		}

		/* Get the user of this token from the DB */
		user, err := users.FindOneUser(users.UserModel{ID: usrUuid})

		if err != nil {
			handleTokenError(optional, token, c, jwt.ValidationError{
				Errors: jwt.ValidationErrorClaimsInvalid,
			}, true)
			return
		}

		if user.Role < roleRequired || (mustBeUpgraded && !hasAdminUpgrade) {
			handleTokenError(optional, token, c, &utils.ErrForbidden{}, false)
			return
		}

		c.Set("session", data)
		c.Set("user", user)
		c.Next()
	}
}

func RefreshSession(c *gin.Context) {
	useri, oku := c.Get("user")
	sessioni, oks := c.Get("session")
	if !oku || !oks { // if not logged in skip refreshing
		c.Next()
		return
	}

	user := useri.(users.UserModel)
	session := *sessioni.(*utils.SessionTokenData)
	now := time.Now().Unix()

	if user.Role == Admin && now < session.AdminAccessToken {
		// refresh admin access token only if currently valid
		session.AdminAccessToken = now + 60*30 /* 30 min */
	}

	newTok, err := utils.CreateMainToken(session)

	if err != nil {
		fmt.Printf("error creating main token on refresh: %v\n", err)
		c.Next()
		return
	}

	SetSession(c, newTok, &user, session.AdminAccessToken)

	c.Next()
}

func ReadSessionAdmin() func(*gin.Context) {
	return ReadSessionEx(false, Admin, true)
}

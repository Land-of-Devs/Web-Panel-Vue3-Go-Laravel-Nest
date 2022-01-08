package users

import (
	"webpanel/core/auth"
	"webpanel/core/users"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/users").Use(auth.ReadSessionAdmin(), auth.RefreshSession)
	ctx.POST("/create", users.UsersCreation)
	ctx.PUT("/:uuid", users.UserUpdate)
	ctx.PUT("/verify", users.UserVerify)
	ctx.DELETE("/", users.UserDelete)
	ctx.GET("/", users.UserList)
}

package users

import (
	"github.com/gin-gonic/gin"
	"webpanel/core/users"
)
func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/users")
		ctx.POST("/create", users.UsersCreation )
		ctx.PUT("/:uuid", users.UserUpdate )
		ctx.DELETE("/", users.UserDelete )
		ctx.GET("/", users.UserList )
	}
}

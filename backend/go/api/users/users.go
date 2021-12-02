package users

import (
	"github.com/gin-gonic/gin"
	"webpanel/core/users"
)
func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/users")
		ctx.GET("/create-user", users.UsersCreation )
	}
}

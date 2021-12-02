package auth

import (
	"webpanel/core/users"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/auth")
		ctx.GET("/admin-session-upgrade", users.UpgradeTokenToAdmin)
	}
}

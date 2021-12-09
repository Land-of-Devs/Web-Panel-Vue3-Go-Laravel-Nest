package auth

import (
	"webpanel/core/auth"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/auth")
	ctx.GET("/test-auth", auth.SetTestToken)
	//ctx.GET("/info", users.ParseToken)
	ctx.GET("/admin-session-upgrade", auth.ReadSessionEx(false, auth.Admin, false), auth.UpgradeTokenToAdmin)
}

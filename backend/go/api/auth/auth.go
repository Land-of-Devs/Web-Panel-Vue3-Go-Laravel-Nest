package auth

import (
	"webpanel/core/auth"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/auth")
	if gin.Mode() == gin.DebugMode {
		ctx.GET("/info", auth.ReadSessionEx(false, auth.User, false), auth.RefreshSession, auth.GetSessionInfo)
		ctx.GET("/test-auth", auth.SetTestToken)
	}

	ctx.POST("/admin-session-upgrade", auth.ReadSessionEx(false, auth.Admin, false), auth.RefreshSession, auth.UpgradeTokenToAdmin)
}

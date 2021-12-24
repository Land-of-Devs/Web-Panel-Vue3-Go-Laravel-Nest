package stats

import (
	"webpanel/core/auth"
	"webpanel/core/stats"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/stats").Use(auth.ReadSessionAdmin(), auth.RefreshSession)
	ctx.POST("/users/created", stats.GetCreatedUserStats)
}

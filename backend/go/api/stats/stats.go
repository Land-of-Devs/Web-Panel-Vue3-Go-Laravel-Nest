package stats

import (
	"webpanel/core/auth"
	"webpanel/core/stats"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/stats").Use(auth.ReadSessionAdmin(), auth.RefreshSession)
	ctx.GET("/created_users", stats.GetCreatedUserStats)
	ctx.GET("/created_products", stats.GetCreatedProductStats)
}

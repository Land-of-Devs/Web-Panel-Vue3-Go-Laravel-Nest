package stats

import (
	"webpanel/core/auth"
	"webpanel/core/stats"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/stats").Use(auth.ReadSessionAdmin(), auth.RefreshSession)
	ctx.GET("/created_users", stats.GetCreatedUserStats)
	ctx.GET("/count_created_users", stats.GetCountCreatedUsers)
	ctx.GET("/created_products", stats.GetCreatedProductStats)
}

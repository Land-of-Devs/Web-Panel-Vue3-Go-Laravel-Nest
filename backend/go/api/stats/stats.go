package stats

import (
	"webpanel/core/stats"

	"github.com/gin-gonic/gin"
)



func InitRoutes(r *gin.RouterGroup) {
	ctx := r.Group("/stats")
	ctx.POST("/users/created", stats.GetCreatedUserStats)
}

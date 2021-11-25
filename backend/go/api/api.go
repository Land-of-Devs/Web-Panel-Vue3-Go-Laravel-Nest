package api

import (
	"webpanel/api/auth"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.Engine) {
	{
		ctx := r.Group("/api/admin")
		auth.InitRoutes(ctx)
	}
}

package api

import (
	"webpanel/api/auth"
	"webpanel/api/users"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.Engine) {
	{
		ctx := r.Group("/api/admin")
		auth.InitRoutes(ctx)
		users.InitRoutes(ctx)
	}
}

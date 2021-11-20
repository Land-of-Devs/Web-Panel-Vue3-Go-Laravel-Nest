package apiv1

import (
	"webpanel/api/v1/auth"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/v1")
		auth.InitRoutes(ctx)
	}
}

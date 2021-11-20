package api

import (
	apiv1 "webpanel/api/v1"

	"github.com/gin-gonic/gin"
)

func InitRoutes(r *gin.Engine) {
	{
		ctx := r.Group("/api/admin")
		apiv1.InitRoutes(ctx)
	}
}

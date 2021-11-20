package auth

import "github.com/gin-gonic/gin"

func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/auth")
		ctx.GET("/status", status)
	}
}

func status(c *gin.Context) {
	c.JSON(200, gin.H{
		"works": true,
	})
}

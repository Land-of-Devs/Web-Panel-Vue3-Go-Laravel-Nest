package auth

import "github.com/gin-gonic/gin"

func InitRoutes(r *gin.RouterGroup) {
	{
		ctx := r.Group("/auth")
		ctx.GET("/status", status)
		ctx.GET("/users", users)
	}
}

func status(c *gin.Context) {
	c.JSON(200, gin.H{
		"works": true,
	})
}

type Tag struct {
	Name        string `json:"name" form:"name" binding:"required"`
	Description string `json:"description" form:"description" binding:"required"`
	ExtraData   string `json:"extra"`
}

func users(c *gin.Context) {
	users := []Tag{{Name: "a", Description: ""}}

	c.JSON(200, gin.H{
		"users": users,
	})
}

package main

import (
	"os"
	"webpanel/api"
	db "webpanel/database"

	"github.com/gin-gonic/gin"
)

func main() {
	db.Connect(
		os.Getenv("DB_HOST"),
		os.Getenv("DB_USERNAME"),
		os.Getenv("DB_PASSWORD"),
		os.Getenv("DB_DATABASE"),
	)

	r := gin.Default()

	api.InitRoutes(r)

	r.Run("0.0.0.0:8080")
}

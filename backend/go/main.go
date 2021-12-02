package main

import (
	"os"
	"webpanel/api"
	"webpanel/core/users"
	"webpanel/db"

	"github.com/gin-gonic/gin"
)

func main() {
	db.Connect(
		os.Getenv("DB_HOST"),
		os.Getenv("DB_USERNAME"),
		os.Getenv("DB_PASSWORD"),
		os.Getenv("DB_DATABASE"),
	)

	migrate()

	r := gin.Default()
	api.InitRoutes(r)

	r.Run("0.0.0.0:8080")
}

func migrate() {
	users.Setup();
}
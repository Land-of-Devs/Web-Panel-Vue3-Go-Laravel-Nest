package main

import (
	"net"
	"os"
	"webpanel/api"
	"webpanel/core/users"
	"webpanel/db"
	"webpanel/utils"

	"github.com/gin-gonic/gin"
)

func main() {
	utils.InitMailClient()

	db.Connect(
		os.Getenv("DB_HOST"),
		os.Getenv("DB_USERNAME"),
		os.Getenv("DB_PASSWORD"),
		os.Getenv("DB_DATABASE"),
	)

	migrate()

	r := gin.Default()
	setTrustedProxies(r)
	api.InitRoutes(r)

	r.Run("0.0.0.0:8080")
}

func setTrustedProxies(r *gin.Engine) {
	ips, err := net.LookupIP("wp_web")

	if err != nil || len(ips) == 0 {
		r.SetTrustedProxies(nil)
		return
	}

	r.SetTrustedProxies([]string{ips[0].String()})
}

func migrate() {
	users.Setup()
}

package db

import (
	"fmt"
	"gorm.io/driver/postgres"
	"gorm.io/gorm"
)

var dbConnection *gorm.DB = nil

func Connect(host string, user string, password string, dbname string) {
	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s sslmode=disable",
		host, user, password, dbname,
	)

	db, err := gorm.Open(postgres.Open(dsn), &gorm.Config{})

	if err != nil {
		panic("Failed to connect to the database (" + err.Error() + ")")
	}

	fmt.Println("Connected to DB!")
	dbConnection = db
}

func GetConnection() *gorm.DB {
	return dbConnection
}

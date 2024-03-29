package products

import (
	"time"
	"webpanel/core/users"
	"webpanel/db"

	"github.com/gofrs/uuid"
)

type ProductModel struct {
	ID           int64           `gorm:"type:int64;primary_key;column:id"`
	Slug         string          `gorm:"type:string;unique;column:slug"`
	Name         string          `gorm:"type:string;unique;column:name"`
	Description  string          `gorm:"type:string;unique;column:description"`
	Price        uint32          `gorm:"type:string;unique;column:price"`
	CreatorRefer *uuid.UUID      `gorm:"type:string;unique;column:creator"`
	Creator      users.UserModel `gorm:"foreignKey:CreatorRefer"`
	Image        *string         `gorm:"type:string;column:image"`
	CreatedAt    time.Time
	UpdatedAt    time.Time
}

func (ProductModel) TableName() string {
	return "products"
}

func FindAllProductsDateRange(fromDate time.Time, toDate time.Time) ([]ProductModel, error) {
	db := db.GetConnection()
	var arrModel []ProductModel

	err := db.Where("created_at BETWEEN ? AND ?", fromDate, toDate).Find(&arrModel).Error

	return arrModel, err
}

func GetProductCount() (int64, error) {
	db := db.GetConnection()
	var count int64

	err := db.Model(&ProductModel{}).Count(&count).Error

	return count, err
}

func GetProductCountYear(year string) (int64, error) {
	db := db.GetConnection()
	var count int64

	err := db.Model(&ProductModel{}).Where("date_part('year', created_at) = ?", year).Count(&count).Error

	return count, err
}

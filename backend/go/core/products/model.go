package products

import (
	"time"
	"webpanel/core/users"
	"webpanel/db"

	"github.com/gofrs/uuid"
	"gorm.io/gorm"
)

type ProductModel struct {
	gorm.Model
	ID int64 `gorm:"type:int64;primary_key;column:id"`
	Slug string `gorm:"type:string;unique;column:slug"`
	Name string `gorm:"type:string;unique;column:name"`
	Description string `gorm:"type:string;unique;column:description"`
	Price uint32 `gorm:"type:string;unique;column:price"`
	CreatorRefer *uuid.UUID `gorm:"type:string;unique;column:creator"`
	Creator users.UserModel `gorm:"foreignKey:CreatorRefer"`
	Image *string `gorm:"type:string;column:image"`
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

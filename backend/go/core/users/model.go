package users

import (
	"errors"
	"webpanel/db"

	"github.com/gofrs/uuid"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

type UserModel struct {
	gorm.Model
	ID           uuid.UUID `gorm:"uuid;primary_key;default:uuid_generate_v3()"`
	Username     string    `gorm:"column:username"`
	Email        string    `gorm:"column:email;unique_index"`
	Image        *string   `gorm:"column:image"`
	Role         uint8     `gorm:"column:role"`
	Verify       bool      `gorm:"column:verify"`
	PasswordHash string    `gorm:"column:password;not null"`
}

// Migrate the schema of database if needed
func Setup() {
	db := db.GetConnection()

	db.AutoMigrate(&UserModel{})
}

func (u *UserModel) BeforeCreate(tx *gorm.DB) (err error) {
  id, err := uuid.NewV4();
  u.ID = id
  return
}

func (u *UserModel) setPassword(password string) error {
	if len(password) == 0 {
		return errors.New("password should not be empty")
	}
	bytePassword := []byte(password)
	// Make sure the second param `bcrypt generator cost` between [4, 32)
	passwordHash, _ := bcrypt.GenerateFromPassword(bytePassword, bcrypt.DefaultCost)
	u.PasswordHash = string(passwordHash)
	return nil
}

func FindOneUser(condition interface{}) (UserModel, error) {
	db := db.GetConnection()
	var model UserModel
	err := db.Where(condition).First(&model).Error
	return model, err
}

func SaveOne(data interface{}) error {
	db := db.GetConnection()

	err := db.Save(data).Error
	return err
}

func (model *UserModel) Update(data interface{}) error {
	db := db.GetConnection()
	err := db.Model(model).Updates(data).Error
	return err
}

package users

import (
	"errors"
	"strconv"
	"webpanel/db"

	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

type UserModel struct {
	gorm.Model
	ID           uuid.UUID `gorm:"type:uuid;primary_key"`
	Username     string    `gorm:"column:username"`
	Email        string    `gorm:"unique;column:email;"`
	Image        *string   `gorm:"column:image"`
	Role         uint8     `gorm:"column:role"`
	Verify       bool      `gorm:"column:verify"`
	PasswordHash string    `gorm:"column:password;not null"`
}
type Tabler interface {
  TableName() string
}

func (UserModel) TableName() string {
  return "users"
}

// Migrate the schema of database if needed
func Setup() {
	db := db.GetConnection()

	db.AutoMigrate(&UserModel{})
}

func (u *UserModel) BeforeCreate(tx *gorm.DB) (err error) {
	id, err := uuid.NewV4()
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

func DeleteUsers(uuids []uuid.UUID) {
	db := db.GetConnection()
	var model UserModel
	db.Delete(&model, uuids)
}

func FindAllUsers(c *gin.Context) ([]UserModel, error) {
	db := db.GetConnection()
	var arrModel []UserModel
	page, _ := strconv.Atoi(c.Query("page"))
	pageSize, _ := strconv.Atoi(c.Query("page_size"))

	err := db.Scopes(utils.Paginate(page, pageSize)).Find(&arrModel).Error
	return arrModel, err
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

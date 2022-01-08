package users

import (
	"errors"
	"fmt"
	"os"
	"strconv"
	"time"
	"webpanel/db"

	"webpanel/utils"

	"github.com/gin-gonic/gin"
	"github.com/gofrs/uuid"
	mail "github.com/xhit/go-simple-mail/v2"
	"golang.org/x/crypto/bcrypt"
	"gorm.io/gorm"
)

type UserModel struct {
	ID            uuid.UUID `gorm:"type:uuid;primary_key"`
	Username      string    `gorm:"uniqueIndex:userIdx;column:username"`
	Hash          int       `gorm:"uniqueIndex:userIdx;column:hash"`
	Email         string    `gorm:"unique;column:email;"`
	Image         *string   `gorm:"column:image"`
	Role          uint8     `gorm:"column:role"`
	Verify        bool      `gorm:"column:verify"`
	PasswordHash  string    `gorm:"column:password;not null"`
	TwoStepSecret string    `gorm:"column:two_step_secret"`
	CreatedAt     time.Time
	UpdatedAt     time.Time
}

func (UserModel) TableName() string {
	return "users"
}

// Migrate the schema of database if needed
func Setup() {
	db := db.GetConnection()

	db.AutoMigrate(&UserModel{})

	db.Exec(`
	CREATE OR REPLACE FUNCTION hash_user_update()
		RETURNS trigger
		LANGUAGE plpgsql
	AS $$
	BEGIN
		WITH taken_hashes AS (
			SELECT hash FROM users
			WHERE username = NEW.username
			ORDER BY hash),
		max_hash AS (
			SELECT coalesce(th.hash, 0) AS max_hash
			FROM (VALUES (0)) AS h(hash)
			LEFT JOIN taken_hashes AS th ON 1=1
			ORDER BY th.hash DESC LIMIT 1),
		avail_hashes AS (
			SELECT hash FROM max_hash AS m, generate_series(0, coalesce(m.max_hash + 1, 0)) AS nums(hash)
			WHERE hash NOT IN (SELECT hash FROM taken_hashes))
		SELECT hash INTO NEW.hash
		FROM avail_hashes
		ORDER BY hash
		LIMIT 1;

		RETURN NEW;
	END;
	$$;
	`)

	db.Exec(`
	CREATE OR REPLACE TRIGGER detect_hash_user_update_biu
	BEFORE INSERT OR UPDATE OF username ON users
	FOR EACH ROW
	EXECUTE PROCEDURE hash_user_update()
	`)

	if gin.Mode() == gin.DebugMode {
		if usr, err := FindOneUser(UserModel{Username: "admin"}); err != nil || usr.ID.IsNil() {
			fmt.Println("-- CREATING DUMMY DEBUG ADMIN USER!! Login: admin:admin, 2fa secret: AAAA AAAA AAAA AAAA AAAA")
			adm := UserModel{
				Role:          uint8(Admin),
				Username:      "admin",
				Email:         os.Getenv("WP_MAIL_USER"),
				Verify:        true,
				TwoStepSecret: "AAAAAAAAAAAAAAAAAAAA",
				Image:         nil,
			}

			adm.setPassword("admin")
			if err := SaveOne(&adm); err != nil {
				fmt.Printf("error creating dummy admin: %v", err)
			}
		}
	}
}

func sendTwoStepSecretMail(u *UserModel) {
	if u.TwoStepSecret == "" {
		return
	}

	m := mail.NewMSG()
	m.AddTo(u.Email)
	m.SetSubject("2FA secret for user " + u.Username + " on WebPanel")
	m.SetBody(mail.TextPlain, "This is your admin two factor auth secret: "+u.TwoStepSecret+"\r\n"+
		"Don't share it with anyone.")
	utils.SendMail(m)
}

func (u *UserModel) BeforeCreate(tx *gorm.DB) (err error) {
	id, err := uuid.NewV4()
	u.ID = id

	if u.Role == uint8(Admin) && u.TwoStepSecret == "" {
		u.TwoStepSecret = utils.GenerateTwoStepSecret()
		sendTwoStepSecretMail(u)
	}

	return
}

func (u *UserModel) AfterUpdate(tx *gorm.DB) (err error) {
	if u.Role < uint8(Admin) && u.TwoStepSecret != "" {
		tx.Model(u).Update("two_step_secret", "")
	} else if u.Role == uint8(Admin) && u.TwoStepSecret == "" {
		tx.Model(u).Update("two_step_secret", utils.GenerateTwoStepSecret())
		sendTwoStepSecretMail(u)
		fmt.Printf("u.TwoStepSecret: %v\n", u.TwoStepSecret)
	}

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

func FindOneUser(condition UserModel) (UserModel, error) {
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
func VerifyUsers(uuids []uuid.UUID) {
	db := db.GetConnection()
	var model UserModel
	db.Model(&model).Where("id IN (?)", uuids).Update("verify", true)
}

func FindAllUsersPag(c *gin.Context) ([]UserModel, utils.PageType, error) {
	db := db.GetConnection()
	var arrModel []UserModel
	var count int64
	page, _ := strconv.Atoi(c.Query("page"))
	pageSize, _ := strconv.Atoi(c.Query("page_size"))

	err := db.Scopes(utils.Paginate(page, pageSize)).Find(&arrModel).Error
	db.Model(&UserModel{}).Count(&count)
	dataPager := utils.Pager(count, pageSize)
	return arrModel, dataPager, err
}

func FindAllUsers() ([]UserModel, error) {
	db := db.GetConnection()
	var arrModel []UserModel

	err := db.Find(&arrModel).Error
	return arrModel, err
}

func SaveOne(data *UserModel) error {
	db := db.GetConnection()
	err := db.Save(data).Error
	return err
}

func (model *UserModel) Update(data UserModel) error {
	db := db.GetConnection()
	err := db.Model(model).Updates(data).Error
	return err
}

func FindAllUsersDateRange(fromDate time.Time, toDate time.Time) ([]UserModel, error) {
	db := db.GetConnection()
	var arrModel []UserModel

	err := db.Where("created_at BETWEEN ? AND ?", fromDate, toDate).Find(&arrModel).Error

	return arrModel, err
}

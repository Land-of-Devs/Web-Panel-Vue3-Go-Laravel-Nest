package users

import (
	"github.com/gofrs/uuid"
)

type UserResponse struct {
	ID       uuid.UUID `json:"ID"`
	Username string    `json:"username"`
	Email    string    `json:"email"`
	Image    *string   `json:"image"`
	Verify   bool      `json:"verify"`
	Role     int8      `json:"role"`
}

func Serialize(myUserModel UserModel) UserResponse {
	user := UserResponse{
		ID:       myUserModel.ID,
		Username: myUserModel.Username,
		Email:    myUserModel.Email,
		Image:    myUserModel.Image,
		Verify:   myUserModel.Verify,
		Role:     int8(myUserModel.Role),
	}

	return user
}

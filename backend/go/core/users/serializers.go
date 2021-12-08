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

type UsersResponse struct {
	Users []UserResponse
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

func MultipleSerialize(UsersModel []UserModel) []UserResponse {
	users := []UserResponse{}
	for _, user := range UsersModel {
		user := Serialize(user)
		users = append(users, user)
	}
	return users
}
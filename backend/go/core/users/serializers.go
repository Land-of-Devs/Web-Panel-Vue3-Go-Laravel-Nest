package users

import (
	"github.com/gofrs/uuid"
	"webpanel/utils"
)

type UserResponse struct {
	ID       uuid.UUID `json:"id"`
	Username string    `json:"username"`
	Email    string    `json:"email"`
	Image    *string   `json:"image"`
	Verify   bool      `json:"verify"`
	Role     int8      `json:"role"`
	Hash     int       `json:"hash"`
}

type UsersResponse struct {
	Users []UserResponse
}

type PagerResponse struct {
	TotalUsers int64 `json:"totalUsers"`
	TotalPages int   `json:"totalPages"`
}

func Serialize(myUserModel UserModel) UserResponse {
	user := UserResponse{
		ID:       myUserModel.ID,
		Username: myUserModel.Username,
		Email:    myUserModel.Email,
		Image:    myUserModel.Image,
		Verify:   myUserModel.Verify,
		Role:     int8(myUserModel.Role),
		Hash:     myUserModel.Hash,
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

func Pager(dataPager utils.PageType) PagerResponse {
	pager := PagerResponse{
		TotalUsers:  dataPager.TotalUsers,
		TotalPages: dataPager.TotalPages,
	}
	return pager
}

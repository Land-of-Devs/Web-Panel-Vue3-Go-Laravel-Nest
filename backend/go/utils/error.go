package utils

import (
	"fmt"

	validator "github.com/go-playground/validator/v10"
)

type ErrForbidden struct{}

func (e *ErrForbidden) Error() string {
	return "Forbidden"
}

type UtilsError struct {
	Errors map[string]interface{} `json:"errors"`
}

func NewValidatorError(err error) UtilsError {
	res := UtilsError{}
	res.Errors = make(map[string]interface{})
	errs := err.(validator.ValidationErrors)
	for _, v := range errs {

		if v.Param() != "" {
			res.Errors[v.Field()] = fmt.Sprintf("{%v: %v}", v.Tag(), v.Param())
		} else {
			res.Errors[v.Field()] = fmt.Sprintf("{key: %v}", v.Tag())
		}

	}
	return res
}

func NewError(key string, err error) UtilsError {
	res := UtilsError{}
	res.Errors = make(map[string]interface{})
	res.Errors[key] = err.Error()
	return res
}

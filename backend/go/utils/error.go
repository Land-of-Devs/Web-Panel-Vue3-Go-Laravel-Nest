package utils

import (
	"fmt"

	validator "github.com/go-playground/validator/v10"
)

type errForbidden struct{}
type errUnauthorized struct{}

func (e *errForbidden) Error() string {
	return "Forbidden"
}

func (e *errUnauthorized) Error() string {
	return "Unauthorized"
}

var ErrForbidden error = &errForbidden{}
var ErrUnauthorized error = &errUnauthorized{}

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
	if err == nil {
		res.Errors[key] = key
	} else {
		res.Errors[key] = err.Error()
	}

	return res
}

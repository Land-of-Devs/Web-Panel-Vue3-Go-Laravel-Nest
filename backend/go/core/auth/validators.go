package auth

import (
	"webpanel/utils"

	"github.com/gin-gonic/gin"
)

type TwoStepCode struct {
	Code string `json:"code" validate:"min=6,max=6,regexp=^[0-9]+$"`
}

func (tsc *TwoStepCode) Bind(c *gin.Context) error {
	err := utils.Bind(c, tsc)
	if err != nil {
		return err
	}

	return nil
}

func NewTwoStepValidator() TwoStepCode {
	twoStepValidator := TwoStepCode{}
	return twoStepValidator
}

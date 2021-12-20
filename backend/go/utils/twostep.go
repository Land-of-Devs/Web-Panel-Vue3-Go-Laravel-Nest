package utils

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"

	"github.com/gin-gonic/gin"
)

type generatedCode struct {
	Data struct {
		Code string `json:"code"`
	} `json:"data"`
}

func GenerateTwoStepSecret() string {
	resp, err := http.Get("http://wp_laravel:3000/api/internal/twostep/generate")

	if err != nil {
		fmt.Printf("error generating twostep code: %v\n", err)
		return ""
	}

	defer resp.Body.Close()

	ok := resp.StatusCode == 200

	if !ok {
		fmt.Printf("error generating twostep code\n")
		if gin.Mode() == gin.DebugMode {
			body, _ := ioutil.ReadAll(resp.Body)

			fmt.Printf("body: %v\n", string(body))
		}

		return ""
	}

	body, _ := ioutil.ReadAll(resp.Body)

	var code generatedCode
	json.Unmarshal(body, &code)

	return code.Data.Code
}

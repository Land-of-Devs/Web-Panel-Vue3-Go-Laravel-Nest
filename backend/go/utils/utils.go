package utils

import (
	"bufio"
	"encoding/json"
	"io/ioutil"
	"os"

	"github.com/gin-gonic/gin"
	"github.com/gin-gonic/gin/binding"
	"gorm.io/gorm"
)

func ReadFileBytes(filename string) ([]byte, error) {
	file, err := os.Open(filename)

	if err != nil {
		return nil, err
	}

	defer file.Close()

	stats, statsErr := file.Stat()
	if statsErr != nil {
		return nil, statsErr
	}

	var size int64 = stats.Size()
	bytes := make([]byte, size)

	bufr := bufio.NewReader(file)
	_, err = bufr.Read(bytes)

	return bytes, err
}

func Bind(c *gin.Context, obj interface{}) error {
	b := binding.Default(c.Request.Method, c.ContentType())
	return c.ShouldBindWith(obj, b)
}

func Paginate(page int, pageSize int) func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {

		if page == 0 {
			page = 1
		}

		switch {
		case pageSize > 100:
			pageSize = 100
		case pageSize <= 0:
			pageSize = 10
		}

		offset := (page - 1) * pageSize

		return db.Offset(offset).Limit(pageSize)
	}
}

func ParseJSON(c *gin.Context) (json.RawMessage, error) {
	jsonData, err := ioutil.ReadAll(c.Request.Body)
	var data json.RawMessage
	if err != nil {
		return nil, err
	}

	json.Unmarshal(jsonData, &data)
	return data, err
}

type PageType struct {
	TotalUsers int64
	TotalPages int
}

func Pager(count int64, pageSize int) PageType {
	float := int(count)/pageSize + 1
	obj := PageType{
		TotalUsers: count,
		TotalPages: float,
	}
	return obj
}

package stats

import (
	"time"
	"webpanel/core/users"

	"github.com/gin-gonic/gin"
)

type RequestStats struct {
	Time string `json:"time" form:"time" binding:"required"`
}

func GetCreatedUserStats(c *gin.Context) {

	userList, err := users.FindAllUsers()

	if err != nil {
		c.Status(500)
		return
	}
	req := RequestStats{}
	c.Bind(&req)

	var s int64
	var list []uint32

	var tsize int64 = 86400

	switch req.Time {
	case "YEAR":
		s = 365
	case "MONTH":
		s = 30
	case "WEEK":
		s = 7
	case "DAY":
		s = 24
		tsize = 3600
	}

	list = make([]uint32, s)

	for _, u := range userList {
		t := u.CreatedAt.Unix()
		p := (time.Now().Unix()/tsize)-(t/tsize);
		if p < s {
			list[p]++
		}
	}

	c.JSON(200, list)
}

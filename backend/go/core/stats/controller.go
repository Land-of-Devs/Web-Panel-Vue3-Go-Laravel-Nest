package stats

import (
	"time"
	"webpanel/core/products"
	"webpanel/core/users"

	"github.com/gin-gonic/gin"
)

type RequestStats struct {
	FromDate string `json:"from_date" form:"from_date" binding:"required"`
	ToDate string `json:"to_date" form:"to_date" binding:"required"`
}

func GetCreatedUserStats(c *gin.Context) {
	req := RequestStats{}
	c.Bind(&req)

	fromDate, err := time.Parse("2006-01-02", req.FromDate)
	if err != nil {
		c.Status(500)
		return
	}

	toDate, err := time.Parse("2006-01-02", req.ToDate)
	if err != nil {
		c.Status(500)
		return
	}

	userList, err := users.FindAllUsersDateRange(fromDate, toDate)
	if err != nil {
		c.Status(500)
		return
	}

	var diffDays uint32 = (uint32(toDate.Sub(fromDate).Hours() / 24))
	var dayseconds int64 = 86400

	if diffDays > 100000 {
		c.Status(500)
		return
	}

	var list []uint32 = make([]uint32, diffDays)
	fdu := fromDate.Unix()

	for _, u := range userList {
		list[(u.CreatedAt.Unix()/dayseconds)-(fdu/dayseconds)]++
	}

	c.JSON(200, gin.H{
		"data": list,
	})
	/*

	tsize, s := getTsizeAndNPoints(req.Time)

	**/
}


func GetCreatedProductStats(c *gin.Context) {
	req := RequestStats{}
	c.Bind(&req)

	fromDate, err := time.Parse("2006-01-02", req.FromDate)
	if err != nil {
		c.Status(500)
		return
	}

	toDate, err := time.Parse("2006-01-02", req.ToDate)
	if err != nil {
		c.Status(500)
		return
	}

	productList, err := products.FindAllProductsDateRange(fromDate, toDate)
	if err != nil {
		c.Status(500)
		return
	}

	var diffDays uint32 = (uint32(toDate.Sub(fromDate).Hours() / 24))
	var dayseconds int64 = 86400

	if diffDays > 100000 {
		c.Status(500)
		return
	}

	var list []uint32 = make([]uint32, diffDays)
	fdu := fromDate.Unix()

	for _, p := range productList {
		if p.CreatorRefer != nil {
			list[(p.CreatedAt.Unix()/dayseconds)-(fdu/dayseconds)]++
		}

	}

	c.JSON(200, list)
	c.JSON(200, gin.H{
		"data": list,
	})
}

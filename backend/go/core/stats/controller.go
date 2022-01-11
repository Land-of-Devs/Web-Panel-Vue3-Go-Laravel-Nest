package stats

import (
	"fmt"
	"time"
	"webpanel/core/products"
	"webpanel/core/users"

	"github.com/gin-gonic/gin"
)

func GetCreatedUserStats(c *gin.Context) {

	fromDate, err := time.Parse("2006-01-02", c.DefaultQuery("from_date", ""))
	if err != nil {
		fmt.Print(err)
		c.Status(500)
		return
	}

	toDate, err := time.Parse("2006-01-02", c.DefaultQuery("to_date", ""))
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
		"data": gin.H{
			"stats": list,
		},
	})
	/*

		tsize, s := getTsizeAndNPoints(req.Time)

		**/
}

func GetCreatedProductStats(c *gin.Context) {

	fromDate, err := time.Parse("2006-01-02", c.DefaultQuery("from_date", ""))
	if err != nil {
		fmt.Print(err)
		c.Status(500)
		return
	}

	toDate, err := time.Parse("2006-01-02", c.DefaultQuery("to_date", ""))
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
		//if p.CreatorRefer != nil {
		list[(p.CreatedAt.Unix()/dayseconds)-(fdu/dayseconds)]++
		//}

	}

	c.JSON(200, gin.H{
		"data": gin.H{
			"stats": list,
		},
	})
}

func GetCountCreatedUsers(c *gin.Context) {

	var count int64
	var err error

	if c.Query("year") == "" {
		count, err = users.GetUserCount()
	} else {
		count, err = users.GetUserCountYear(c.Query("year"))
	}

	if err != nil {
		c.Status(500)
		return
	}

	c.JSON(200, gin.H{
		"data": gin.H{
			"total": count,
		},
	})
}

func GetCountCreatedProducts(c *gin.Context) {
	var count int64
	var err error

	if c.Query("year") == "" {
		count, err = products.GetProductCount()
	} else {
		count, err = products.GetProductCountYear(c.Query("year"))
	}

	if err != nil {
		c.Status(500)
		return
	}

	c.JSON(200, gin.H{
		"data": gin.H{
			"total": count,
		},
	})

}

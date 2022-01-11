package utils

import (
	"fmt"
	"os"
	"strconv"
	"time"

	mail "github.com/xhit/go-simple-mail/v2"
)

// mail sending example:
// m := mail.NewMSG()
// m.AddTo("landofdevs@gmail.com") // destination
// m.SetSubject("This is a test mail")
// m.SetBody(mail.TextPlain, "Test mail from go")
// utils.SendMail(m)

var mailCli *mail.SMTPClient
var mailQueue chan *mail.Email
var mailFrom string
var mailDisabled bool = false

func getMailClient() *mail.SMTPClient {
	if mailCli != nil {
		return mailCli
	}

	var err error
	conn := mail.NewSMTPClient()

	conn.Host = os.Getenv("WP_MAIL_HOST")
	conn.Port, _ = strconv.Atoi(os.Getenv("WP_MAIL_PORT"))
	conn.Username = os.Getenv("WP_MAIL_USER")
	conn.Password = os.Getenv("WP_MAIL_PASS")
	conn.Encryption = mail.EncryptionSTARTTLS

	mailCli, err = conn.Connect()

	if err != nil {
		panic(err)
	}

	return mailCli
}

func mailSendLoop() {
	defer func() {
		if err := recover(); err != nil {
			fmt.Printf("error in mail queue: %v\n", err)
		}

		fmt.Println("Mail queue stopped, disabling mail sending.")
		mailDisabled = true
	}()

	fmt.Println("Initialized mail queue.")
	client := getMailClient()
	for mail := range mailQueue {
		if err := client.Noop(); err != nil {
			// the connection is probably closed, try to fix the issue by reconnecting
			mailCli = nil
			client = getMailClient()
		}

		err := mail.Send(client)

		if err != nil {
			fmt.Printf("error while sending email: %v\n", err)
		}

		time.Sleep(250 * time.Millisecond)
	}
}

func SendMail(msg *mail.Email) {
	msg.SetFrom(mailFrom)

	if msg.Error != nil {
		panic(msg.Error)
	}

	if mailDisabled {
		fmt.Println("Ignoring mail send request")
		return
	}

	mailQueue <- msg
}

func InitMailClient() {
	if os.Getenv("WP_MAIL_HOST") == "" {
		fmt.Println("Mail environment configuration not found, disabling mail sending")
		mailDisabled = true
		return
	}

	mailFrom = os.Getenv("WP_MAIL_NAME") + "<" + os.Getenv("WP_MAIL_USER") + ">"
	mailQueue = make(chan *mail.Email, 100)
	go mailSendLoop()
}

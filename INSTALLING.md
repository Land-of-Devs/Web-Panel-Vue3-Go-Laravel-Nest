# WebPanel install and configuration steps

Example environment file on `config/private/dev/super-secrets.env`:
```
WP_MAIL_HOST=smtp.gmail.com
WP_MAIL_PORT=587
WP_MAIL_NAME=WebPanel
WP_MAIL_USER=webpanel_sender_email@gmail.com
WP_MAIL_PASS=account_password
```

The `WP_MAIL_USER` variable will also be used as the email on the dummy admin user.

If you prefer to not configure mail sending, the file can be reduced to:
```
WP_MAIL_USER=admin@gmail.com
```

Then start the project with: `docker-compose up`
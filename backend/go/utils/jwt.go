package utils

import (
	"fmt"
	"os"
	"time"

	"github.com/golang-jwt/jwt"
)

type SessionTokenData struct {
	AdminAccessToken int64
	jwt.StandardClaims
}

// var (
// 	privateKey *rsa.PrivateKey
// 	publicKey  *rsa.PublicKey
// )

func CreateMainToken(data SessionTokenData) (string, error) {
	data.IssuedAt = time.Now().Unix()
	data.ExpiresAt = time.Now().Unix() + 60*60*24*2 /* 2 days */
	data.Issuer = "WPGo"

	token := jwt.NewWithClaims(jwt.GetSigningMethod("HS256"), data)
	return token.SignedString(GetJwtMainPassphrase())
}

func ParseMainToken(token string) (*jwt.Token, error) {
	return jwt.ParseWithClaims(token, &SessionTokenData{}, func(t *jwt.Token) (interface{}, error) {
		if _, ok := t.Method.(*jwt.SigningMethodHMAC); !ok {
			return nil, fmt.Errorf("unexpected signing method: %v", t.Header["alg"])
		}

		return GetJwtMainPassphrase(), nil
	})
}

func GetJwtMainPassphrase() []byte {
	return []byte(os.Getenv("JWT_PASSPHRASE"))
}

// func GetJwtMainPrivateKey() *rsa.PrivateKey {
// 	var bytes []byte
// 	var key *rsa.PrivateKey
// 	var err error

// 	if privateKey != nil {
// 		return privateKey
// 	}

// 	if bytes, err = ReadFileBytes(os.Getenv("JWT_MAIN_RSA_PATH") + "/private.pem"); err != nil {
// 		fmt.Printf("Couldn't open private key: %v\n", err)
// 		return nil
// 	}

// 	if key, err = jwt.ParseRSAPrivateKeyFromPEMWithPassword(bytes, os.Getenv("JWT_MAIN_RSA_PASSPHRASE")); err != nil {
// 		fmt.Printf("Couldn't parse private key: %v\n", err)
// 		return nil
// 	}

// 	privateKey = key
// 	return key
// }

// func GetJwtMainPublicKey() *rsa.PublicKey {
// 	var bytes []byte
// 	var key *rsa.PublicKey
// 	var err error

// 	if publicKey != nil {
// 		return publicKey
// 	}

// 	if bytes, err = ReadFileBytes(os.Getenv("JWT_MAIN_RSA_PATH") + "/public.pem"); err != nil {
// 		fmt.Printf("Couldn't open public key: %v\n", err)
// 		return nil
// 	}

// 	if key, err = jwt.ParseRSAPublicKeyFromPEM(bytes); err != nil {
// 		fmt.Printf("Couldn't parse public key: %v\n", err)
// 		return nil
// 	}

// 	publicKey = key
// 	return key
// }

package utils

import (
	"crypto/rsa"
	"fmt"
	"os"

	"github.com/golang-jwt/jwt"
)

var (
	privateKey *rsa.PrivateKey
	publicKey  *rsa.PublicKey
)

func GetJwtMainPrivateKey() *rsa.PrivateKey {
	var bytes []byte
	var key *rsa.PrivateKey
	var err error

	if privateKey != nil {
		return privateKey
	}

	if bytes, err = ReadFileBytes(os.Getenv("JWT_MAIN_RSA_PATH") + "/private.pem"); err != nil {
		fmt.Printf("Couldn't open private key: %v\n", err)
		return nil
	}

	if key, err = jwt.ParseRSAPrivateKeyFromPEMWithPassword(bytes, os.Getenv("JWT_MAIN_RSA_PASSPHRASE")); err != nil {
		fmt.Printf("Couldn't parse private key: %v\n", err)
		return nil
	}

	privateKey = key
	return key
}

func GetJwtMainPublicKey() *rsa.PublicKey {
	var bytes []byte
	var key *rsa.PublicKey
	var err error

	if publicKey != nil {
		return publicKey
	}

	if bytes, err = ReadFileBytes(os.Getenv("JWT_MAIN_RSA_PATH") + "/public.pem"); err != nil {
		fmt.Printf("Couldn't open public key: %v\n", err)
		return nil
	}

	if key, err = jwt.ParseRSAPublicKeyFromPEM(bytes); err != nil {
		fmt.Printf("Couldn't parse public key: %v\n", err)
		return nil
	}

	publicKey = key
	return key
}

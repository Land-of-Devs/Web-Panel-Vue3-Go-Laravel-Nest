import { CookieOptions } from 'express';
import { JwtModuleOptions } from "@nestjs/jwt";

export const AppJWTModuleOptions = {
  secret: process.env.JWT_PASSPHRASE,
  signOptions: {
    expiresIn: '2d',
    algorithm: 'HS256',
    issuer: "WPNest"
  }
} as JwtModuleOptions;

export const AppCookieOptions = {
  jwt: <CookieOptions> {
    maxAge: 24 * 60 * 60 * 1000 * 2, // 2 days
    sameSite: 'strict',
    httpOnly: true/*,
    secure: true*/
  },
  userdata: <CookieOptions> {
    sameSite: 'strict',
    maxAge: 24 * 60 * 60 * 1000 * 2/*, // 2 days
    secure: true*/
  },
  adminaccess: <CookieOptions> {
    sameSite: 'strict',
    maxAge: 1000 * 60 * 30 // 30 min
  }
};

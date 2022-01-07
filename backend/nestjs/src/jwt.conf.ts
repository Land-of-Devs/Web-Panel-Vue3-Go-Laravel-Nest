import { JwtModuleOptions } from "@nestjs/jwt";

export default {
  secret: process.env.JWT_PASSPHRASE,
  signOptions: {
    expiresIn: '2d',
    algorithm: 'HS256',
    issuer: "WPNest"
  }
} as JwtModuleOptions;

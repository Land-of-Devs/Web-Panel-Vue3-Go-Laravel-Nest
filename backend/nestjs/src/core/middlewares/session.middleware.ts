import { JWTCookieOptions } from './../../access/access.controller';
import { UserService } from './../services/user.service';
import { Injectable, NestMiddleware } from '@nestjs/common';
import { Request, Response, NextFunction } from 'express';
import { JwtService } from "@nestjs/jwt";
import { JWTPayload } from '../interfaces/jwt.interface';

@Injectable()
export class SessionMiddleware implements NestMiddleware {
  constructor(
    private jwt: JwtService,
    private userService: UserService
  ) { }

  private refreshToken(tokenData) {
    return this.jwt.sign({
      AdminAccessToken: tokenData.AdminAccessToken
    } as JWTPayload, {
      subject: tokenData.sub
    }); 
  }

  async use(req: Request, res: Response, next: NextFunction) {
    req.user = null;
    const { session } = req.cookies;
    if (!session) {
      res.status(400).json({error: 'Not logged in!'});
      return;
    }

    try {
      const data = this.jwt.verify(session);

      if (data && data.sub) {
        req.user = await this.userService.getByUUID(data.sub);

        /* Refresh the token */
        res.cookie('session', this.refreshToken(data), JWTCookieOptions);
      }

    } catch (e) {
      res.clearCookie('session', JWTCookieOptions);
      res.status(400).json({error: 'Invalid token!'});
      return;
    }

    next();
  }
}

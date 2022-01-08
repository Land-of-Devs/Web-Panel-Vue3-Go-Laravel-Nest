import { UserService } from './../services/user.service';
import { Injectable, NestMiddleware, BadRequestException } from '@nestjs/common';
import { Request, Response, NextFunction } from 'express';
import { JwtService } from "@nestjs/jwt";
import { JWTPayload } from '../interfaces/jwt.interface';
import { AppCookieOptions } from 'src/jwt.conf';

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

  private clearCookies(res: Response) {
    res.clearCookie('session', {...AppCookieOptions.jwt, maxAge: -1});
    res.clearCookie('userdata', {...AppCookieOptions.userdata, maxAge: -1});
    res.clearCookie('adminaccess', {...AppCookieOptions.adminaccess, maxAge: -1});
  }

  async use(req: Request, res: Response, next: NextFunction) {
    req.user = null;
    const { session } = req.cookies;
    if (!session) {
      this.clearCookies(res);
      throw new BadRequestException('Not logged in!');
    }

    try {
      const data = this.jwt.verify(session);

      if (data && data.sub) {
        const user = await this.userService.getByUUID(data.sub);
        req.user = user;

        /* Refresh the token & user data */
        res.cookie('session', this.refreshToken(data), AppCookieOptions.jwt);
        res.cookie('userdata', JSON.stringify(user.serialize()), AppCookieOptions.userdata);
      }

    } catch (e) {
      this.clearCookies(res);
      throw new BadRequestException('Invalid token!');
      return;
    }

    next();
  }
}

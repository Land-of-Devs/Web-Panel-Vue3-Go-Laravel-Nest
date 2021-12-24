import { SigninDto } from './dto/signin.dto';
import { Body, Controller, ForbiddenException, Post, Res } from '@nestjs/common';
import { SignupDto } from './dto/signup.dto';
import { CookieOptions, Response } from 'express';
import { AccessService } from './access.service';

@Controller('access')
export class AccessController {

  constructor(
    private accessService: AccessService
  ) {
  }

  private JWTCookieOptions: CookieOptions = {
    maxAge: 24 * 60 * 60 * 1000 * 2, // 2 days
    httpOnly: true/*,
    secure: true*/
  }

  @Post('signin')
  async signin(@Body() signinDto: SigninDto, @Res() res: Response) {
    const user = (await this.accessService.validateEmailPassword(signinDto)).serialize();
    res.cookie('session', this.accessService.generateAccessToken(user.id), this.JWTCookieOptions);
    res.json(user);
  }

  @Post('signup')
  async signup(@Body() signupDto: SignupDto, @Res() res: Response) {
    const user = (await this.accessService.createUser(signupDto)).serialize();
    res.cookie('session', this.accessService.generateAccessToken(user.id), this.JWTCookieOptions);
    res.json(user);
  }
}

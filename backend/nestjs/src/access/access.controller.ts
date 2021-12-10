import { SigninDto } from './dto/signin.dto';
import { Body, Controller, ForbiddenException, Post, Res } from '@nestjs/common';
import { SignupDto } from './dto/signup.dto';
import { Response } from 'express';
import { AccessService } from './access.service';

@Controller('access')
export class AccessController {

  constructor(
    private accessService: AccessService
  ) {

  }

  @Post('signin')
  async signin(@Body() signinDto: SigninDto, @Res() res: Response) {
    const user = await this.accessService.validateUserPassword(signinDto);
    delete user.password;
    res.cookie('token', this.accessService.generateAccessToken(user.id));
    res.json(user);
  }

  @Post('signup')
  async signup(@Body() signupDto: SignupDto, @Res() res: Response) {
    const user = await this.accessService.createUser(signupDto);
    delete user.password;
    res.cookie('token', this.accessService.generateAccessToken(user.id));
    res.json(user);
  }
}

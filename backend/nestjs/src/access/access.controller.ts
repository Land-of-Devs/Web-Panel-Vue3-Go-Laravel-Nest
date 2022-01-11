import { SigninDto } from './dto/signin.dto';
import { Body, Controller, ForbiddenException, Get, Param, Post, Res } from '@nestjs/common';
import { SignupDto } from './dto/signup.dto';
import { Response } from 'express';
import { AccessService } from './access.service';
// import { MailService} from '../mail/mail.service';
import { AppCookieOptions } from 'src/jwt.conf';

@Controller('access')
export class AccessController {

    constructor(
        private accessService: AccessService,
        // private mailService: MailService
    ) {
    }

    @Post('signin')
    async signin(@Body() signinDto: SigninDto, @Res() res: Response) {
        const user = (await this.accessService.validateEmailPassword(signinDto)).serialize();
        const token = this.accessService.generateAccessToken(user.id);

        res.cookie('session', token, AppCookieOptions.jwt);
        res.cookie('userdata', JSON.stringify(user), AppCookieOptions.userdata);

        res.json({ ok: true });
    }

    @Post('signup')
    async signup(@Body() signupDto: SignupDto, @Res() res: Response) {
        const user = (await this.accessService.createUser(signupDto)).serialize();
        const token = this.accessService.generateVerifyToken(user.id);

        // await this.mailService.sendUserVerify(user, token);
        res.json({ ok: true });
    }

    @Get('signout')
    async signout(@Res() res: Response) {
        res.clearCookie('session', { ...AppCookieOptions.jwt, maxAge: -1 });
        res.clearCookie('userdata', { ...AppCookieOptions.userdata, maxAge: -1 });
        res.clearCookie('adminaccess', { ...AppCookieOptions.adminaccess, maxAge: -1 });
        res.end();
    }

    @Get('verify')
    async verify(@Param('token') tkn: string, @Res() res: Response) {
        const user = (await this.accessService.verifyUser(tkn))
        const token = this.accessService.generateAccessToken(user.id);
        res.cookie('session', token, AppCookieOptions.jwt);
        res.cookie('userdata', JSON.stringify(user), AppCookieOptions.userdata);

        res.json({ ok: true });
    }
}

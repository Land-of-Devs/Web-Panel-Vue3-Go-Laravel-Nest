import { Req } from '@nestjs/common';
import { SigninDto } from './dto/signin.dto';
import { Body, Controller, ForbiddenException, Get, Param, Post, Res, UseGuards } from '@nestjs/common';
import { SignupDto } from './dto/signup.dto';
import { Response } from 'express';
import { AccessService } from './access.service';
import { MailService } from '../mail/mail.service';
import { AppCookieOptions } from 'src/jwt.conf';
import { AuthGuard } from '@nestjs/passport';

@Controller('access')
export class AccessController {

    constructor(
        private accessService: AccessService,
        private mailService: MailService
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
        this.mailService.sendUserVerify(user, token)
        .catch(e => {
            console.log('Error sending mail', e);
        });

        res.json({ ok: true });
    }

    @Get('google')
    @UseGuards(AuthGuard('google'))
    async googleAuth(@Req() req) {}

    @Get('google/redirect')
    @UseGuards(AuthGuard('google'))
    async googleAuthRedirect(@Res() res: Response, @Req() req) {
        const user = await this.accessService.googleLogin(req.user);
        const token = this.accessService.generateAccessToken(user.id);

        res.cookie('session', token, AppCookieOptions.jwt);
        res.cookie('userdata', JSON.stringify(user), AppCookieOptions.userdata);

        res.redirect('/');
    }

    @Get('signout')
    async signout(@Res() res: Response) {
        res.clearCookie('session', { ...AppCookieOptions.jwt, maxAge: -1 });
        res.clearCookie('userdata', { ...AppCookieOptions.userdata, maxAge: -1 });
        res.clearCookie('adminaccess', { ...AppCookieOptions.adminaccess, maxAge: -1 });
        res.end();
    }

    @Get('verify/:token')
    async verify(@Param('token') tkn: string, @Res() res: Response) {
        const user = (await this.accessService.verifyUser(tkn))
        const token = this.accessService.generateAccessToken(user.id);
        res.cookie('session', token, AppCookieOptions.jwt);
        res.cookie('userdata', JSON.stringify(user), AppCookieOptions.userdata);
        res.json({ ok: true });
    }
}
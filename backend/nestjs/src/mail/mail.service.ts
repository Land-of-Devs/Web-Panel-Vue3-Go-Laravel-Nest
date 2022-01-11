// //import { MailerService } from '@nestjs-modules/mailer';
// import { Injectable } from '@nestjs/common';
// import { UserEntity } from '../core/entities/user.entity';

// @Injectable()
// export class MailService {
//     constructor(private mailerService: MailerService) { }

//     async sendUserVerify(user: UserEntity, token: string) {
//         const url = `localhost/verify/${token}`;

//         await this.mailerService.sendMail({
//             to: user.email,
//             subject: 'Welcome to Web Panel! Confirm your Account',
//             template: './verify',
//             context: {
//                 name: user.username,
//                 url,
//             },
//         });
//     }
// }

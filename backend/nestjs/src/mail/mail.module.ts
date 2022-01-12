import { MailerModule } from '@nestjs-modules/mailer';
import { HandlebarsAdapter } from '@nestjs-modules/mailer/dist/adapters/handlebars.adapter';
import { Global, Module } from '@nestjs/common';
import { MailService } from './mail.service';
import { join } from 'path';

@Global()
@Module({
    imports: [
        MailerModule.forRootAsync({
            useFactory: async () => ({

                transport: {
                    host: process.env.WP_MAIL_HOST,
                    secure: false,
                    auth: {
                        user: process.env.WP_MAIL_USER,
                        pass: process.env.WP_MAIL_PASS,
                    },
                },
                defaults: {
                    from: `"No Reply" <${process.env.MAIL_FROM}>`,
                },
                template: {
                    dir: join(__dirname, 'templates'),
                    adapter: new HandlebarsAdapter(),
                    options: {
                        strict: true,
                    },
                },
            }),
        }),
    ],
    providers: [MailService],
    exports: [MailService],
})
export class MailModule { }
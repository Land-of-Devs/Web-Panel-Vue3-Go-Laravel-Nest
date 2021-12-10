import { Module } from '@nestjs/common';
import { AccessController } from './access.controller';
import { CoreModule } from 'src/core/core.module';
import { AccessService } from './access.service';
import { JwtModule } from '@nestjs/jwt';

@Module({
  imports: [
    CoreModule,
    JwtModule.register({
      secret: process.env.JWT_PASSPHRASE,
      signOptions: {
        expiresIn: '2d',
        algorithm: 'HS256'
      }
    })
  ],
  controllers: [AccessController],
  providers: [AccessService]
})
export class AccessModule {
}

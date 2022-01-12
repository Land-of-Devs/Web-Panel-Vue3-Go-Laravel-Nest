import { GoogleStrategy } from './../core/strategies/google.strategy';
import { Module } from '@nestjs/common';
import { AccessController } from './access.controller';
import { CoreModule } from 'src/core/core.module';
import { AccessService } from './access.service';
import { JwtModule } from '@nestjs/jwt';
import { AppJWTModuleOptions } from 'src/jwt.conf';

@Module({
  imports: [
    CoreModule,
    JwtModule.register(AppJWTModuleOptions)
  ],
  controllers: [AccessController],
  providers: [AccessService, GoogleStrategy]
})
export class AccessModule {
}

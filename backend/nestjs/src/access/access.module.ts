import { Module } from '@nestjs/common';
import { AccessController } from './access.controller';
import { CoreModule } from 'src/core/core.module';
import { AccessService } from './access.service';
import { JwtModule } from '@nestjs/jwt';
import jwtconf from '../jwt.conf';

@Module({
  imports: [
    CoreModule,
    JwtModule.register(jwtconf)
  ],
  controllers: [AccessController],
  providers: [AccessService]
})
export class AccessModule {
}

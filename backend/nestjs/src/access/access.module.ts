import { Module } from '@nestjs/common';
import { AccessController } from './access.controller';
import { UserService } from 'src/core/services/user.service';
import { CoreModule } from 'src/core/core.module';

@Module({
  imports: [CoreModule],
  controllers: [AccessController],
  providers: [UserService]
})
export class AccessModule {
  
}

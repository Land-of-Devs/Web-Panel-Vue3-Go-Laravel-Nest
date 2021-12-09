import { TypeOrmModule } from '@nestjs/typeorm';
import { Module } from '@nestjs/common';
import { UserEntity } from './entities/user.entity';
import { UserService } from './services/user.service';

@Module({
  imports: [
  TypeOrmModule.forFeature([
    UserEntity,
  ]),
],
  providers: [
    UserService
  ],
  exports: [
    TypeOrmModule,
    UserService
  ]
  
})
export class CoreModule {}
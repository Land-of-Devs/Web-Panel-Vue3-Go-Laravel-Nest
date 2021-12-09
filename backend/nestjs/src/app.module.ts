import { UserEntity } from './core/entities/user.entity';
import { Module } from '@nestjs/common';
import { AccessModule } from './access/access.module';
import { TypeOrmModule } from '@nestjs/typeorm';
import dbconf from './db.conf';
import { ClientsModule, Transport } from '@nestjs/microservices';
import { CoreModule } from './core/core.module';

@Module({
  imports: [
    TypeOrmModule.forRoot(dbconf), 
    CoreModule,
    AccessModule
  ],

  providers: [

  ],
  exports: [
  ]
})
export class UserModule {}

import { ProductService } from './services/product.service';
import { TicketService } from './services/ticket.service';
import { TicketEntity } from './entities/ticket.entity';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Module } from '@nestjs/common';
import { UserEntity } from './entities/user.entity';
import { UserService } from './services/user.service';
import { ProductEntity } from './entities/product.entity';

@Module({
  imports: [
  TypeOrmModule.forFeature([
    UserEntity,
    TicketEntity,
    ProductEntity
  ]),
],
  providers: [
    UserService,
    TicketService,
    ProductService
  ],
  exports: [
    TypeOrmModule,
    UserService,
    TicketService,
    ProductService
  ]
  
})
export class CoreModule {}
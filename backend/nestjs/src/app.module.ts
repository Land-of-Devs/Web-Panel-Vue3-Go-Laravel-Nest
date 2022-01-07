import { ProductController } from './product/product.controller';
import { TicketModule } from './ticket/ticket.module';
import { JwtModule } from '@nestjs/jwt';
import { TicketController } from './ticket/ticket.controller';
import { SessionMiddleware } from './core/middlewares/session.middleware';
import { UserEntity } from './core/entities/user.entity';
import { Global, MiddlewareConsumer, Module, NestModule } from '@nestjs/common';
import { AccessModule } from './access/access.module';
import { TypeOrmModule } from '@nestjs/typeorm';
import dbconf from './db.conf';
import jwtconf from './jwt.conf';
import { CoreModule } from './core/core.module';
import { ProductModule } from './product/product.module';

@Module({
  imports: [
    TypeOrmModule.forRoot(dbconf), 
    CoreModule,
    AccessModule,
    TicketModule,
    ProductModule,
    JwtModule.register(jwtconf)
  ],

  providers: [

  ],
  exports: [
  ]
})
export class UserModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
      consumer
        .apply(SessionMiddleware)
        .forRoutes(TicketController, ProductController);
  }
}

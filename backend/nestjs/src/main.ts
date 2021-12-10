import { ValidationPipe } from '@nestjs/common';
import { NestFactory } from '@nestjs/core';
import * as cookieParser from 'cookie-parser';
import { UserModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(UserModule);
  app.setGlobalPrefix('/api/user/');
  app.useGlobalPipes(
    new ValidationPipe({transform: true})
  );
  app.use(cookieParser());
  await app.listen(3000);
}
bootstrap();

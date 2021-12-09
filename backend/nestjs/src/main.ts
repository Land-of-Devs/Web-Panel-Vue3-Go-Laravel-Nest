import { ValidationPipe } from '@nestjs/common';
import { NestFactory } from '@nestjs/core';
import { TcpOptions, Transport } from '@nestjs/microservices';
import { UserModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create(UserModule);
  app.setGlobalPrefix('/api/user/');
  app.useGlobalPipes(
    new ValidationPipe({transform: true})
  );
  await app.listen(3000);
}
bootstrap();

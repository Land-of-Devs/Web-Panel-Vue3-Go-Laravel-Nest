import { ProductController } from './product.controller';
import { Module } from '@nestjs/common';
import { CoreModule } from 'src/core/core.module';

@Module({
  imports: [
    CoreModule
  ],
  controllers: [ProductController],
  providers: []
})
export class ProductModule {
}

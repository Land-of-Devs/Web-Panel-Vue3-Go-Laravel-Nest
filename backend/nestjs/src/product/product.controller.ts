import { Request, Response } from 'express';
import { ProductService } from './../core/services/product.service';
import { UserEntity } from './../core/entities/user.entity';
import { User } from './../core/decorators/user.decorator';
import { Controller, Get, Req, Res } from "@nestjs/common";

@Controller('product')
export class ProductController {
  constructor(private prodServ: ProductService) {}

  @Get('list')
  async list(@User() user: UserEntity, @Req() req: Request, @Res() res: Response) {
    const page = Math.max(+req.query['page'] || 1, 1);
    const { data, total } = await this.prodServ.getPaged(page, 9);
    res.json({
      data: {
        list: {
          data: data.map(p => p.serializeFor(user)),
          current_page: page,
          last_page: Math.ceil(total / 9) 
        }
      },
    });
  }
}

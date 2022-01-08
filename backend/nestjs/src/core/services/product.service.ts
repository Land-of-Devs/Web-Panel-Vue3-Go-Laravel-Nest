import { ItemStatus } from './../enums/itemstatus.enum';
import { UserEntity } from './../entities/user.entity';
import { ProductEntity } from './../entities/product.entity';
import { Injectable } from "@nestjs/common";
import { InjectRepository } from "@nestjs/typeorm";
import { Repository } from 'typeorm';

interface PagedProducts {
  data: ProductEntity[],
  total: number
}

@Injectable()
export class ProductService {
  constructor(@InjectRepository(ProductEntity) private prodRepo: Repository<ProductEntity>) {}

  async getPaged(page: number, perPage: number = 10): Promise<PagedProducts> {
    const [prods, total] = await this.prodRepo.findAndCount({
      where: {status: ItemStatus.COMPLETE},
      take: perPage,
      skip: perPage * (page - 1)
    });

    return {
      data: prods,
      total
    };
  }

  async getByID(id: number): Promise<ProductEntity> {
    return await this.prodRepo.findOne({id, status: ItemStatus.COMPLETE});
  }

  async getByOwner(creator: UserEntity): Promise<ProductEntity[]> {
    return await this.prodRepo.find({user: creator, status: ItemStatus.COMPLETE});
  }

}

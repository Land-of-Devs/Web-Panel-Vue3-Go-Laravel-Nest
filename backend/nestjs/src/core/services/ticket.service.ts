import { ProductService } from './product.service';
import { ItemStatus } from './../enums/itemstatus.enum';
import { UserEntity } from './../entities/user.entity';
import { TicketEntity, TicketType } from './../entities/ticket.entity';
import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { TicketProductCreateContent, TicketProductReportContent, TicketUserUpdateContent } from '../interfaces/ticket.interface';

@Injectable()
export class TicketService {
  constructor(
    @InjectRepository(TicketEntity) private ticketRepo: Repository<TicketEntity>,
    private productService: ProductService,
    ) {}

  async getByID(id: number): Promise<TicketEntity> {
    return await this.ticketRepo.findOne({id});
  }

  private async create(title: string, type: TicketType, owner: UserEntity, content: any): Promise<TicketEntity> {
    const newTicket = new TicketEntity(title, type, owner, content, ItemStatus.PENDING);
    await newTicket.save();
    return newTicket;
  }

  async createProductReq(title: string, owner: UserEntity, content: TicketProductCreateContent) {
    return await this.create(title, TicketType.CREATE_P, owner, content);
  }

  async createProductReport(title: string, owner: UserEntity, content: TicketProductReportContent) {
    const prod = await this.productService.getByID(content.productId);
    if (!prod) {
      throw new Error('Specified Product ID does not exist');
    }

    return await this.create(title, TicketType.REPORT_P, owner, content);
  }

  async createUserUpdateReq(title: string, owner: UserEntity, content: TicketUserUpdateContent) {
    return await this.create(title, TicketType.UPDATE_U, owner, content);
  }
}

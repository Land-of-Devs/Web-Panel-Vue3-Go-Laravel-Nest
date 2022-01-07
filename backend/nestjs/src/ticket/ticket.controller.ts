import { TicketUserUpdateContent } from './../core/interfaces/ticket.interface';
import { Response } from 'express';
import { UserEntity } from './../core/entities/user.entity';
import { User } from './../core/decorators/user.decorator';
import { UserUpdateDto } from './dto/userupd.dto';
import { ProductReportDto } from './dto/productrep.dto';
import { ProductRequestDto } from './dto/productreq.dto';
import { TicketService } from './../core/services/ticket.service';
import { Body, Controller, Post, Req, Res } from "@nestjs/common";

@Controller('ticket')
export class TicketController {

  constructor(
    private ticketService: TicketService
  ) {}

  @Post('product/request')
  async createProdRequest(@User() user: UserEntity, @Res() res: Response, @Body() productRequestDto: ProductRequestDto) {
    const { title, message } = productRequestDto;

    try {
      const ticket = await this.ticketService.createProductReq(title, user, {
        message
      });

      res.json(ticket.serializeFor(user));
    } catch (e) {
      console.error(e);
      res.status(500).json({error: 'Could not create the ticket.'});
    }
  }

  @Post('product/report')
  async createProdReport(@User() user: UserEntity, @Res() res: Response, @Body() productRepDto: ProductReportDto) {
    const { title, productId, message } = productRepDto;

    try {
      const ticket = await this.ticketService.createProductReport(title, user, {
        productId, message
      });

      res.json(ticket.serializeFor(user));
    } catch (e) {
      res.status(500).json({error: 'Could not create the ticket.'});
    }
  }

  @Post('user/update')
  async createUserUpdRequest(@User() user: UserEntity, @Res() res: Response, @Body() userUpdRequestDto: UserUpdateDto) {
    const { name, email, password } = userUpdRequestDto;
    const title = 'User update request for ' + user.username + '#' + user.hash.toString().padStart(4, '0');

    try {
      const tdata = <TicketUserUpdateContent> {};
      if (name && name != user.username) {
        tdata.newName = name;
      }

      if (email && email != user.email) {
        tdata.newEmail = email;
      }

      if (password) {
        user.password = password;
        await user.hashPassword();
        tdata.newPasswordHash = user.password;
      }

      if (Object.keys(tdata).length == 0) {
        res.status(400).json({error: 'No changes requested!'});
        return;
      }

      const ticket = await this.ticketService.createUserUpdateReq(title, user, tdata);

      res.json({id: ticket.id});
    } catch (e) {
      console.error(e);
      res.status(500).json({error: 'Could not create the ticket.'});
    }
  }
}

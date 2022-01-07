import { TicketController } from './ticket.controller';
import { Module } from '@nestjs/common';
import { CoreModule } from 'src/core/core.module';

@Module({
  imports: [
    CoreModule
  ],
  controllers: [TicketController],
  providers: []
})
export class TicketModule {
}

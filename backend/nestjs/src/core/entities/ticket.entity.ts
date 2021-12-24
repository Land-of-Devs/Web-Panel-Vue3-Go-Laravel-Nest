import { UserEntity } from 'src/core/entities/user.entity';
import { BaseEntity, Column, CreateDateColumn, Entity, ManyToOne, PrimaryGeneratedColumn } from "typeorm";

export enum TicketType {
  REPORT_P = 'Report Product',
  CREATE_P = 'Create Product',
  UPDATE_U = 'Update User'
}

export enum TicketStatus {
  PENDING = 'Pending',
  ACCEPT = 'Accept',
  CANCELLED = 'Cancelled',
  COMPLETE = 'Complete'
}

@Entity({name: 'tickets'})
export class TicketEntity extends BaseEntity {

  constructor(title: string, type: TicketType, owner: UserEntity, content: any, status: TicketStatus) {
    super();
    this.title = title;
    this.type = type;
    this.sent_at = new Date();
    this.user = owner.id;
    this.content = content;
    this.status = status;
  }

  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  title: string;

  @Column()
  type: string;

  @CreateDateColumn()
  public sent_at: Date;

  @ManyToOne(() => UserEntity)
  user: string;

  @Column({ type: 'json' })
  content: any;

  @Column()
  status: string;

  serialize() {
    return {...this};
  }
}
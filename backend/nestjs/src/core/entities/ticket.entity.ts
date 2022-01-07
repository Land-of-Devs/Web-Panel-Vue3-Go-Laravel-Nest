import { ItemStatus } from './../enums/itemstatus.enum';
import { UserEntity } from 'src/core/entities/user.entity';
import { BaseEntity, Column, CreateDateColumn, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn, UpdateDateColumn } from "typeorm";

export enum TicketType {
  REPORT_P = 'Report Product',
  CREATE_P = 'Create Product',
  UPDATE_U = 'Update User'
}

@Entity({name: 'tickets'})
export class TicketEntity extends BaseEntity {

  constructor(title: string, type: TicketType, owner: UserEntity, content: any, status: ItemStatus) {
    super();
    this.title = title;
    this.type = type;
    this.created_at = new Date();
    this.updated_at = this.created_at;
    this.creator = owner;
    this.content = content;
    this.status = status;
  }

  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  title: string;

  @Column()
  type: string;
  
  @JoinColumn({ name: 'creator' })
  @ManyToOne(() => UserEntity, usr => usr.id, { eager: true })
  creator: UserEntity;
  
  @Column({ type: 'json' })
  content: any;
  
  @Column()
  status: string;
  
  @CreateDateColumn()
  public created_at: Date;

  @UpdateDateColumn()
  public updated_at: Date;

  serialize() {
    const data = {...this};
    data.creator = data.creator?.serialize();

    return data;
  }

  serializeFor(user: UserEntity = null) {
    const data = {...this};
    data.creator = data.creator?.serializeFor(user);
    
    return data;
  }
}
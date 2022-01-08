import { ItemStatus } from './../enums/itemstatus.enum';
import { UserEntity } from './user.entity';
import { Column, CreateDateColumn, Entity, JoinColumn, ManyToOne, PrimaryGeneratedColumn, UpdateDateColumn } from "typeorm";

@Entity({ name: 'products' })
export class ProductEntity {

  constructor(id: number, name: string, description: string, price: number, creator: UserEntity, image: string, status: ItemStatus) {
    this.id = id;
    this.name = name;
    this.description = description;
    this.price = price;
    this.user = creator;
    this.image = image;
    this.status = status;
    this.created_at = new Date();
    this.updated_at = this.created_at;
    this.slug = (name+'').replace(/[^a-z0-9]/ig, '-') + '-' + this.created_at.getTime();
  }

  @PrimaryGeneratedColumn()
  id: number;
  
  @Column()
  slug: string;
  
  @Column()
  name: string;
  
  @Column()
  description: string;
  
  @Column()
  price: number;
  
  @JoinColumn({ name: 'creator' })
  @ManyToOne(() => UserEntity, usr => usr.id, { nullable: true, eager: true })
  user: UserEntity;
  
  @Column()
  image: string;

  @Column()
  status: string;

  @CreateDateColumn()
  created_at: Date;

  @UpdateDateColumn()
  updated_at: Date;

  serialize() {
    const data = {...this};
    data.user = data.user?.serialize();

    return data;
  }

  serializeFor(user: UserEntity = null) {
    const data = {...this};
    data.user = data.user?.serializeFor(user);
    
    return data;
  }
}

import { DefaultValuePipe } from "@nestjs/common";
import { randomUUID } from "node:crypto";
import { BaseEntity, Column, CreateDateColumn, DeleteDateColumn, Entity, PrimaryColumn, UpdateDateColumn } from "typeorm";

@Entity({name: 'users'})
export class UserEntity extends BaseEntity {

  @PrimaryColumn()
  id: string = randomUUID();

  @CreateDateColumn()
  public created_at: Date = new Date();

  @UpdateDateColumn()
  public updated_at: Date;

  @DeleteDateColumn()
  public deleted_at: Date;

  @Column()
  username: string;

  @Column()
  email: string;

  @Column()
  image: string = '';

  @Column()
  role: number;

  @Column()
  verify: boolean = false;

  @Column()
  password: string;
}
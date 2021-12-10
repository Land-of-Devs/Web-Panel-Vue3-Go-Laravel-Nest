import { DefaultValuePipe } from "@nestjs/common";
import { randomUUID } from "node:crypto";
import { BaseEntity, BeforeInsert, Column, CreateDateColumn, DeleteDateColumn, Entity, PrimaryColumn, UpdateDateColumn } from "typeorm";
import * as bcrypt from 'bcrypt';

@Entity({name: 'users'})
export class UserEntity extends BaseEntity {

  constructor(email: string, username: string, password: string) {
    super();
    this.id = randomUUID();
    this.created_at = new Date();
    this.email = email;
    this.username = username;
    this.password = password;
    this.role = 0;
    this.verify = false;
  }

  @PrimaryColumn()
  id: string;

  @CreateDateColumn()
  public created_at: Date;

  @UpdateDateColumn()
  public updated_at: Date;

  @DeleteDateColumn()
  public deleted_at: Date;

  @Column()
  username: string;

  @Column()
  email: string;

  @Column()
  image: string;

  @Column()
  role: number;

  @Column()
  verify: boolean;

  @Column()
  password: string;

  @BeforeInsert()
  async hashPassword() {
    const salt = await bcrypt.genSalt();
    this.password = await bcrypt.hash(this.password, salt);
  }

  async validatePassword(password: string): Promise<boolean> {
    return await bcrypt.compare(password, this.password);
  }
}
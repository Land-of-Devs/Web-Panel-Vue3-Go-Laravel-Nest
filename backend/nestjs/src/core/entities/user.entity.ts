import { randomUUID } from "node:crypto";
import { BaseEntity, BeforeInsert, Column, CreateDateColumn, DeleteDateColumn, Entity, PrimaryColumn, UpdateDateColumn } from "typeorm";
import * as bcrypt from 'bcrypt';

export enum UserRole {
  GUEST,
  USER,
  EMPLOYEE,
  ADMIN
}

@Entity({name: 'users'})
export class UserEntity extends BaseEntity {

  constructor(email: string, username: string, password: string) {
    super();
    this.id = randomUUID();
    this.created_at = new Date();
    this.email = email;
    this.username = username;
    this.hash = null;
    this.password = password;
    this.role = UserRole.USER;
    this.verify = false;
    this.two_step_secret = null;
  }

  @PrimaryColumn({ type: 'uuid' })
  id: string;

  @CreateDateColumn()
  public created_at: Date;

  @UpdateDateColumn()
  public updated_at: Date;

  @Column()
  username: string;

  @Column()
  hash: number;

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

  @Column()
  two_step_secret: string;

  @BeforeInsert()
  async hashPassword() {
    const salt = await bcrypt.genSalt();
    this.password = await bcrypt.hash(this.password, salt);
  }

  async validatePassword(password: string): Promise<boolean> {
    return await bcrypt.compare(password, this.password);
  }

  serialize() {
    let data = {...this};
    delete data.password;
    delete data.two_step_secret;
    return data;
  }

  serializeFor(user: UserEntity = null) {
    const data = this.serialize();
    if (!user || user.id != this.id) {
      delete data.email;
    }

    return data;
  }
}
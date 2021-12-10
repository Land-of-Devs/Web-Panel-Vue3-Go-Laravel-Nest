import { randomUUID } from 'node:crypto';
import { BadRequestException, HttpException, Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { QueryFailedError, Repository } from 'typeorm';
import { SignupDto } from 'src/access/dto/signup.dto';
import { UserEntity } from '../entities/user.entity';

@Injectable()
export class UserService {
  constructor(@InjectRepository(UserEntity) private userRepo: Repository<UserEntity>) {}

  async getByUUID(id: string): Promise<UserEntity> {
    return await this.userRepo.findOne({id});
  }

  async getByEmail(email: string) {
    return await this.userRepo.findOne({email});
  }

  async create(email: string, username: string, password: string): Promise<UserEntity> {
    const newUser = new UserEntity(email, username, password);
    
    try {
      await newUser.save();
    } catch(e) {
      if (e.code == 23505 && e.constraint == 'users_email_key') {
        throw new BadRequestException('Email already taken');
      }
      throw e;
    }
     
    return newUser;
  }
}

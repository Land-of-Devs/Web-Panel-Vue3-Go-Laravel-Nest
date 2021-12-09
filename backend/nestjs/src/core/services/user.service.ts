import { randomUUID } from 'node:crypto';
import { UserEntity } from './../entities/user.entity';
import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { SignupDto } from 'src/access/dto/signup.dto';
import * as bcrypt from 'bcrypt';

@Injectable()
export class UserService {
  constructor(@InjectRepository(UserEntity) private userRepo: Repository<UserEntity>) {}

  get(username) {
    this.userRepo.findOne({username});
  }

  new(dto: SignupDto) {
    const newUser = new UserEntity();
    newUser.id = randomUUID();
    newUser.username = dto.username;
    newUser.email = dto.email;
    newUser.role = 0;
    newUser.password = bcrypt.hashSync(dto.password, 5);
    newUser.verify = false;
    
    newUser.save();
  }
}

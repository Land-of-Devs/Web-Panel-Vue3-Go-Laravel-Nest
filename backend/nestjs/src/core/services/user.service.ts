import { BadRequestException, Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { UserEntity } from '../entities/user.entity';

@Injectable()
export class UserService {
    constructor(@InjectRepository(UserEntity) private userRepo: Repository<UserEntity>) { }

    async getByUUID(id: string): Promise<UserEntity> {
        return await this.userRepo.findOne({ id });
    }

    async getByEmail(email: string) {
        return await this.userRepo.findOne({ email });
    }

    async verify(user: UserEntity): Promise<UserEntity> {
        try {
            user.verify = true;
            await user.save();
        } catch (e) {
            throw e;
        }
        return user;
    }

    async create(email: string, username: string, password: string): Promise<UserEntity> {
        const newUser = new UserEntity(email, username, password);

        try {
            await newUser.save();
        } catch (e) {
            if (e.code == 23505 && e.constraint == 'users_email_key') {
                throw new BadRequestException('Email already taken');
            }
            throw e;
        }

        return newUser;
    }
}

import { BadRequestException, ForbiddenException, Injectable, NotFoundException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { UserEntity } from "src/core/entities/user.entity";
import { JWTPayload } from "src/core/interfaces/jwt.interface";
import { UserService } from "src/core/services/user.service";
import { SigninDto } from "./dto/signin.dto";
import { SignupDto } from "./dto/signup.dto";


@Injectable()
export class AccessService {

    constructor(
        private usersService: UserService,
        private jwtService: JwtService
    ) {

    }

    async validateEmailPassword(dto: SigninDto): Promise<UserEntity> {
        const user = await this.usersService.getByEmail(dto.email);
        if (user) {
            const valid = await user.validatePassword(dto.password);

            if (valid && !user.verify) {
                throw new ForbiddenException('User not verified!');
            } else if (valid) {
                return user;
            }
        }

        throw new ForbiddenException('Invalid email or password');
    }

    async createUser(dto: SignupDto) {
        return await this.usersService.create(dto.email, dto.username, dto.password);
    }

    async verifyUser(id: string) {
        let user = await this.usersService.getByUUID(id);
        if (user) {
            return await this.usersService.verify(user);
        } else {
            throw new NotFoundException();
        }
    }

    generateAccessToken(id: string) {
        return this.jwtService.sign({
            AdminAccessToken: 0
        } as JWTPayload, {
            subject: id
        });
    }

    generateVerifyToken(id: string) {
        return this.jwtService.sign({
            subject: id,
            secret: process.env.JWT_VERIFY
        });
    }

}

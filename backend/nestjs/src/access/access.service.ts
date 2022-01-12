import { ForbiddenException, Injectable, NotFoundException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { UserEntity } from "src/core/entities/user.entity";
import { JWTPayload } from "src/core/interfaces/jwt.interface";
import { UserService } from "src/core/services/user.service";
import { GoogleUser } from "src/core/strategies/google.strategy";
import { MailService } from "src/mail/mail.service";
import { SigninDto } from "./dto/signin.dto";
import { SignupDto } from "./dto/signup.dto";


@Injectable()
export class AccessService {

    constructor(
        private usersService: UserService,
        private jwtService: JwtService,
        private mailService: MailService,
    ) {

    }

    async googleLogin(gUser: GoogleUser): Promise<UserEntity> {
        if (!gUser) {
            throw new ForbiddenException('No user from google'); 
        }

        const user = await this.usersService.getByEmail(gUser.email);
        if (user) {
            return user;
        }

        let name = gUser.firstName + (gUser.lastName ? ' ' + gUser.lastName : '');
        if (name.length > 24) {
            name = gUser.firstName.slice(0, 24);
        }

        return await this.usersService.create(gUser.email, name, '', true/*, gUser.picture*/);
    }

    async validateEmailPassword(dto: SigninDto): Promise<UserEntity> {
        const user = await this.usersService.getByEmail(dto.email);
        if (user) {
            const valid = await user.validatePassword(dto.password);

            if (valid && !user.verify) {
                const token = this.generateVerifyToken(user.id);
                await this.mailService.sendUserVerify(user, token);
                throw new ForbiddenException(`This account isn't verify, we sent an email with a new verification!`);
            } else if (valid) {
                return user;
            }
        }

        throw new ForbiddenException('Invalid email or password');
    }

    async createUser(dto: SignupDto) {
        return await this.usersService.create(dto.email, dto.username, dto.password);
    }

    async verifyUser(token: string) {
        try {
            const payload = this.jwtService.verify(token, { secret: process.env.JWT_VERIFYPHRASE })
            let user = await this.usersService.getByUUID(payload.id);
            if (user) {
                return await this.usersService.verify(user);
            } else {
                throw new NotFoundException('User not found!');
            }
        } catch (e) {
            throw new ForbiddenException('Token is Invalid!');
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
        return this.jwtService.sign({ id: id }, { secret: process.env.JWT_VERIFYPHRASE, expiresIn: '20m' });
    }

}

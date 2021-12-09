import { SigninDto } from './dto/signin.dto';
import { Body, Controller, Post } from '@nestjs/common';
import { SignupDto } from './dto/signup.dto';
import { UserService } from 'src/core/services/user.service';

@Controller('access')
export class AccessController {

  constructor(private userService: UserService) {

  }

  @Post('signin')
  signin(@Body() signinData: SigninDto) {
    return 'a';
    
  }

  @Post('signup')
  signup(@Body() signupData: SignupDto) {
    this.userService.new(signupData);
  }
}

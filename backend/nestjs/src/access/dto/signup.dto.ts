import { IsEmail, IsString, MaxLength, MinLength } from "class-validator";

export class SignupDto {
  
  @IsString()
  username: string;

  @IsEmail()
  email: string;

  @IsString()
  @MinLength(4)
  @MaxLength(100)
  password: string;

}


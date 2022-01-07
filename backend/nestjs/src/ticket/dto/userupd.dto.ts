import { IsEmail, IsOptional, IsString, MaxLength, MinLength } from "class-validator";

export class UserUpdateDto {
  @IsString()
  @IsOptional()
  @MinLength(2)
  @MaxLength(24)
  name: string | null;

  @IsEmail()
  @IsOptional()
  email: string | null;

  @IsString()
  @MinLength(4)
  @MaxLength(100)
  @IsOptional()
  password: string | null;
}

import { IsBoolean, IsString, MaxLength, MinLength } from "class-validator";

export class ProductRequestDto {
  @IsString()
  @MinLength(4)
  @MaxLength(64)
  title: string;

  @IsString()
  @MinLength(10)
  @MaxLength(1000)
  message: string;
}

import { IsNumber, IsString, MaxLength, MinLength } from "class-validator";

export class ProductReportDto {
  @IsString()
  @MinLength(4)
  @MaxLength(64)
  title: string;

  @IsNumber({ allowInfinity: false, allowNaN: false, maxDecimalPlaces: 0 })
  productId: number;

  @IsString()
  @MinLength(10)
  @MaxLength(255)
  message: string;
}
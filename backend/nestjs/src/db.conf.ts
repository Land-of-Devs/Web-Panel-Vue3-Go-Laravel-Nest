import { TypeOrmModuleOptions } from '@nestjs/typeorm';

export default {
  type: 'postgres',
  host: 'wp_db',
  port: 5432,
  username: 'postgres',
  password: 'password',
  database: 'web_panel',
  autoLoadEntities: true,
  synchronize: false
} as TypeOrmModuleOptions;

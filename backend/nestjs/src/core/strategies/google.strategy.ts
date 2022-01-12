import { PassportStrategy } from '@nestjs/passport';
import { Strategy } from 'passport-google-oauth20';

import { Injectable } from '@nestjs/common';

export interface GoogleUser {
  email: string;
  firstName: string;
  lastName: string | null;
  picture: string | null;
  accessToken: string;
}

@Injectable()
export class GoogleStrategy extends PassportStrategy(Strategy, 'google') {

  constructor() {
    super({
      clientID: process.env.GOOGLE_CLIENT_ID || "none",
      clientSecret: process.env.GOOGLE_SECRET || "none",
      callbackURL: process.env.BASE_URL + '/api/user/access/google/redirect',
      scope: ['email', 'profile'],
    });
  }

  async validate (accessToken: string, refreshToken: string, profile: any): Promise<GoogleUser> {
    const { name, emails, photos } = profile;
    const user = {
      email: emails[0].value,
      firstName: name.givenName,
      lastName: name.familyName || null,
      picture: (photos[0] || {}).value || null,
      accessToken
    };
    
    return user;
  }
}

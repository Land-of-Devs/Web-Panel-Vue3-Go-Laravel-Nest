import * as api from './api';

export function signIn(form) {
  return api.post('/user/access/signin/', form);
}

export function signUp(form) {
  return api.post('/user/access/signup/', form);
}

export function signOut(form) {
  return api.get('/user/access/signout/', form);
}
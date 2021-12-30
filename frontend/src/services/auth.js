import * as api from './api';

export function signIn(form){
    return api.post('/user/access/signin/', form);
}

export function signUp(form){
    return api.post('/user/access/signup/', form);
}
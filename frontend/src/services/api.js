import axios from "axios";

export function get(url, params = {}){
    return axios.get(url, {params});
}

export function post(url, body, params = {}) {
    return axios.post(url, body, {params});
}

export function put(url, body){
    return axios.put(url, body);
}

export function del(url, data){
    return axios.delete(url, {data});
}

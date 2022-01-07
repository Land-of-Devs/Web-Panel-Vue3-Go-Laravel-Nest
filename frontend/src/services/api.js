import axios from "axios";

export function get(url, params = {}){
    return axios.get(url, {params});
}

export function post(url, body, params = {}) {
    return axios.post(url, body, {params});
}

export function put(url, body, params = {}){
    return axios.put(url, body, {params});
}

export function del(url, data){
    return axios.delete(url, {data});
}

import * as api from './api';

//------[ STAFF ENDPOINTS ]------\\
export async function create(form) {
    let res = await api.post('/admin/users/create', form);
    return res.data;
}

export async function update(id ,form){
    let res = await api.put('/admin/users/'+ id, form);
    return res.data;
}

export async function del(uuids){
    return await api.del('/admin/users', uuids);
}

export async function usersList(page, search){
    let res = await api.get('/admin/users', { page: page, page_size: 10, search: search});
    return res.data;
}

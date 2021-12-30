import * as api from './api';

//------[ PRIVATE FUNCTIONS ]------\\
function constructList(res){
    return {
        list: res.data.list.data,
        page: res.data.list.current_page,
        totalPages: res.data.list.last_page,
    }
}

//------[ STAFF ENDPOINTS ]------\\
export async function detailsStaff(slug) {

    return await api.get('/staff/products/'+ slug);
}

export async function create(form) {
    return api.post('/staff/products', form);
}

export async  function update(slug ,form){
    return api.post('/staff/products/'+ slug, form, {'_method': 'PUT'});
}

export async function del(slugs){
    return api.del('/staff/products/delete', slugs);
}

export async function status(slugs){
    return api.put('/staff/products/status', slugs);
}

export async function staffList(){
    let res = await api.get('/staff/products/all');
    return constructList(res.data);
}

export async function myList(){
    let res = await api.get('/staff/products');
    return constructList(res.data);
    
}

//------[ CLIENT ENDPOINTS ]------\\
export async function clientList(){
    let res = await api.get('/user/products');
    return constructList(res.data);
}

export async function detailsClient(slug){
    let res = await api.get('user/details/' + slug );
    return constructList(res.data);
}
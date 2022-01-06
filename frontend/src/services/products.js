import * as api from './api';

//------[ PRIVATE FUNCTIONS ]------\\
function constructList(res){
    return {
        list: res.data.list.data,
        page: res.data.list.current_page,
        totalPages: res.data.list.last_page,
    }
}

function constructProduct(res){
    return {
        product: res.data.product
    }
}

//------[ STAFF ENDPOINTS ]------\\
export async function detailsStaff(slug) {
    let res = await api.get('/staff/products/'+ slug);
    return constructProduct(res.data);
}

export async function create(form) {
    let res = await api.post('/staff/products/', form);
    return constructProduct(res.data);
}

export async function update(slug ,form){
    let res = await api.post('/staff/products/'+ slug, form, {'_method': 'PUT'});
    return constructProduct(res.data);
}

export async function del(slugs){
    return api.del('/staff/products/delete', slugs);
}

export async function status(slugs){
    return api.put('/staff/products/status', slugs);
}

export async function staffList(page, status){
    let res = await api.get('/staff/products/all', { page: page, status: status});
    return constructList(res.data);
}

export async function myList(page, status){
    let res = await api.get('/staff/products', { page: page, status: status });
    return constructList(res.data);
    
}

//------[ CLIENT ENDPOINTS ]------\\
export async function clientList(page, status){
    let res = await api.get('/user/products', {page: page, status: status});
    return constructList(res.data);
}

export async function detailsClient(slug){
    let res = await api.get('user/details/' + slug );
    return constructList(res.data);
}
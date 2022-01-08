import * as api from './api';

//------[ PRIVATE FUNCTIONS ]------\\
function constructList(res) {
    return {
        list: res.data.list.data,
        total: res.data.list.total,
        totalPages: res.data.list.last_page,
    }
}

function constructTicket(res) {
    return {
        ticket: res.data.ticket
    }
}

//------[ STAFF ENDPOINTS ]------\\
export async function detailsTicket(id) {
    let res = await api.get('/staff/tickets/' + id);
    return constructTicket(res.data);
}

export async function del(ids) {
    let res = await api.del('/staff/tickets/delete', ids);
    return res.data.data;
}

export async function status(ids) {
    let res = await api.put('/staff/tickets/status', ids);
    return res.data.data;
}

export async function ticketList(page, status, type) {
    let res = await api.get('/staff/tickets', { page: page, status: status, type: type });
    return constructList(res.data);

}

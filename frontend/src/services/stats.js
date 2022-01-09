import * as api from './api';

export async function createdTickets(form) {
  return (await api.get('/staff/stats/tickets', form)).data;
}

export async function createdProducts(form) {
  return (await api.get('/admin/stats/created_products', form)).data;
}

export async function createdUsers(form) {
  return (await api.get('/admin/stats/created_users', form)).data;
}

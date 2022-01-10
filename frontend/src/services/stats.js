import * as api from './api';

export async function createdTickets(form) {
  return (await api.get('/staff/stats/tickets', form)).data.data;
}

export async function createdProducts(form) {
  return (await api.get('/admin/stats/created_products', form)).data.data;
}

export async function createdUsers(form) {
  return (await api.get('/admin/stats/created_users', form)).data.data;
}

export async function totalCreatedUsers(form) {
  return (await api.get('/admin/stats/count_created_users', form)).data.data;
}

export async function totalCreatedProducts(form) {
  return (await api.get('/admin/stats/count_created_products', form)).data.data;
}

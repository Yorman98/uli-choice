import type { AxiosPromise } from 'axios'
import ApiService from '@/services'

class ClientService {
  getClients(): AxiosPromise<any> {
    return ApiService.get('/clients')
  }

  getClientById(id: any): AxiosPromise<any> {
    return ApiService.get(`/clients/${id}`)
  }

  createClient(body: any): AxiosPromise<any> {
    return ApiService.post('/clients', body)
  }

  updateClient(id: any, body: any): AxiosPromise<any> {
    return ApiService.put(`/clients/${id}`, body)
  }

  deleteClient(id: any): AxiosPromise<any> {
    return ApiService.delete(`/clients/${id}`)
  }
}

export default new ClientService()

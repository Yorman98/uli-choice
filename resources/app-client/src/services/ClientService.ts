import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { ClientResponseInterface } from '@/services/types/ClientTypes'
import type { ClientInterface } from '@/store/types/ClientInterface'

class ClientService {
  getClients(): AxiosPromise<ClientResponseInterface> {
    return ApiService.get('/clients')
  }

  getClientById(id: number): AxiosPromise<ClientResponseInterface> {
    return ApiService.get(`/clients/${id}`)
  }

  createClient(payload: UnwrapNestedRefs<ClientInterface>): AxiosPromise<ClientResponseInterface> {
    return ApiService.post('/clients', payload)
  }

  updateClient(payload: UnwrapNestedRefs<ClientInterface>): AxiosPromise<ClientResponseInterface> {
    return ApiService.put(`/clients/${payload.id}`, payload)
  }

  deleteClient(id: number): AxiosPromise<ClientResponseInterface> {
    return ApiService.delete(`/clients/${id}`)
  }
}

export default new ClientService()

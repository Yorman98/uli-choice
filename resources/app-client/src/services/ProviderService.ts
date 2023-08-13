import ApiService from '@/services'
import type { ProviderResponseInterface } from '@/services/types/ProviderTypes'
import type { AxiosPromise } from 'axios'

class ProviderService {
  getProviders(): AxiosPromise<ProviderResponseInterface> {
    return ApiService.get('/provider')
  }

  getProviderById(id: any): AxiosPromise<any> {
    return ApiService.get(`/provider/${id}`)
  }

  createProvider(body: any): AxiosPromise<any> {
    return ApiService.post('/provider', body)
  }

  updateProvider(id: any, body: any): AxiosPromise<any> {
    return ApiService.put(`/provider/${id}`, body)
  }

  deleteProvider(id: any): AxiosPromise<any> {
    return ApiService.delete(`/provider/${id}`)
  }
}

export default new ProviderService()

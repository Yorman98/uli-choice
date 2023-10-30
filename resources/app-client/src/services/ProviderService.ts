import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { ProviderResponseInterface } from '@/services/types/ProviderTypes'
import type { ProviderInterface } from '@/store/types/ProviderInterface'

class ProviderService {
  getProviders(): AxiosPromise<ProviderResponseInterface> {
    return ApiService.get('/provider')
  }

  getProviderById(id: number): AxiosPromise<ProviderResponseInterface> {
    return ApiService.get(`/provider/${id}`)
  }

  createProvider(payload: UnwrapNestedRefs<ProviderInterface>): AxiosPromise<ProviderResponseInterface> {
    return ApiService.post('/provider', payload)
  }

  updateProvider(payload: UnwrapNestedRefs<ProviderInterface>): AxiosPromise<ProviderResponseInterface> {
    return ApiService.put(`/provider/${payload.id}`, payload)
  }

  deleteProvider(id: number): AxiosPromise<ProviderResponseInterface> {
    return ApiService.delete(`/provider/${id}`)
  }
}

export default new ProviderService()

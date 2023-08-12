import ApiService from '@/services'
import type { ProviderResponseInterface } from '@/services/types/ProviderTypes'

class ProviderService {
  getProviders(): Promise<ProviderResponseInterface> {
    return ApiService.get('/provider')
  }
}

export default new ProviderService()

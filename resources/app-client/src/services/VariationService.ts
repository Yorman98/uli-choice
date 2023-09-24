import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { VariationResponseInterface } from '@/services/types/VariationTypes'
import type { VariationInterface } from '@/store/types/VariationInterface'

class VariationService {
  createOrUpdateVariation(productId: number, payload: UnwrapNestedRefs<VariationInterface>): AxiosPromise<VariationResponseInterface> {
    return ApiService.post(`/product/${productId}/variations`, payload)
  }

  getVariationByProduct(productId: number): AxiosPromise<VariationResponseInterface> {
    return ApiService.get(`/product/${productId}/variations`)
  }

  deleteVariation(productId: number, id: number): AxiosPromise<VariationResponseInterface> {
    return ApiService.delete(`/product/${productId}/variations/${id}`)
  }
}

export default new VariationService()

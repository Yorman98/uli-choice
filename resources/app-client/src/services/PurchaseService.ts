import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { PurchaseResponseInterface } from '@/services/types/PurchaseTypes'
import type { PurchaseInterface } from '@/store/types/PurchaseInterface'

class PurchaseService {
  createPurchase(payload: UnwrapNestedRefs<PurchaseInterface>): AxiosPromise<PurchaseResponseInterface> {
    return ApiService.post('/purchase', payload)
  }

  getPurchases(): AxiosPromise<PurchaseResponseInterface> {
    return ApiService.get('/purchase')
  }

  getPurchase(id: number): AxiosPromise<PurchaseResponseInterface> {
    return ApiService.get(`/purchase/${id}`)
  }

  updatePurchase(payload: UnwrapNestedRefs<PurchaseInterface>): AxiosPromise<PurchaseResponseInterface> {
    return ApiService.put(`/purchase/${payload.id}`, payload)
  }

  deletePurchase(id: number): AxiosPromise<PurchaseResponseInterface> {
    return ApiService.delete(`/purchase/${id}`)
  }
}

export default new PurchaseService()

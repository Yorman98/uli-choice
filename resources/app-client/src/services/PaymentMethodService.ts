import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { PaymentMethodResponseInterface } from '@/services/types/PaymentMethodTypes'
import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'

class PaymentMethodService {
  createPaymentMethod(payload: UnwrapNestedRefs<PaymentMethodInterface>): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.post('/payment-methods', payload)
  }

  getPaymentMethods(): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.get('/payment-methods')
  }

  deletePaymentMethod(id: number): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.delete(`/payment-methods/${id}`)
  }
}

export default new PaymentMethodService()

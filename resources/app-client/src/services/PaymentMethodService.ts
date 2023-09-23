import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { PaymentMethodResponseInterface } from '@/services/types/PaymentMethodTypes'
import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'

class PaymentMethodService {
  createPaymentMethod(payload: UnwrapNestedRefs<PaymentMethodInterface>): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.post('/payment-method', payload)
  }

  getPaymentMethods(): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.get('/payment-method')
  }

  getPaymentMethod(id: number): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.get(`/payment-method/${id}`)
  }

  updatePaymentMethod(payload: UnwrapNestedRefs<PaymentMethodInterface>): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.put(`/payment-method/${payload.id}`, payload)
  }

  deletePaymentMethod(id: number): AxiosPromise<PaymentMethodResponseInterface> {
    return ApiService.delete(`/payment-method/${id}`)
  }
}

export default new PaymentMethodService()

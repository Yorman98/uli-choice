import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { OrderResponseInterface } from '@/services/types/OrderTypes'
import type { OrderInterface } from '@/store/types/OrderInterface'

export class OrderService {
  createOrder(payload: UnwrapNestedRefs<OrderInterface>): AxiosPromise<OrderResponseInterface> {
    return ApiService.post('/orders', payload)
  }

  getOrder(id: number): AxiosPromise<OrderResponseInterface> {
    return ApiService.get(`/orders/${id}`)
  }

  getOrders(): AxiosPromise<OrderResponseInterface> {
    return ApiService.get('/orders')
  }

  updateOrder(payload: UnwrapNestedRefs<OrderInterface>): AxiosPromise<OrderResponseInterface> {
    return ApiService.put(`/orders/${payload.id}`, payload)
  }

  // Download invoice PDF
  getInvoice(id: number): AxiosPromise<Blob> {
    return ApiService.get(`/orders/${id}/invoice`, {
      headers: {
        Accept: 'application/pdf',
        responseType: 'blob'
      }
    })
  } 
  
  deleteOrder(id: number): AxiosPromise<OrderResponseInterface> {
    return ApiService.delete(`/orders/${id}`)
  }
}

export default new OrderService()

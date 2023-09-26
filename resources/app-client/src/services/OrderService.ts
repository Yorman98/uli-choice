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

  deleteOrder(id: number): AxiosPromise<OrderResponseInterface> {
    return ApiService.delete(`/orders/${id}`)
  }
}

export default new OrderService()

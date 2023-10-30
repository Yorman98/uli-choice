import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { TransactionResponseInterface } from '@/services/types/TransactionTypes'
import type { TransactionInterface } from '@/store/types/TransactionInterface'

class TransactionService {
  createTransaction(payload: UnwrapNestedRefs<TransactionInterface>): AxiosPromise<TransactionResponseInterface> {
    return ApiService.post('/transactions', payload)
  }

  getTransaction(orderId: number): AxiosPromise<TransactionResponseInterface> {
    return ApiService.get(`/transactions/${orderId}`)
  }

  updateTransaction(payload: UnwrapNestedRefs<TransactionInterface>): AxiosPromise<TransactionResponseInterface> {
    return ApiService.put(`/transactions/${payload.id}`, payload)
  }

  deleteTransaction(id: number): AxiosPromise<TransactionResponseInterface> {
    return ApiService.delete(`/transactions/${id}`)
  }
}

export default new TransactionService()

import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { TransactionResponseInterface } from '@/services/types/TransactionTypes'
import type { TransactionInterface } from '@/store/types/TransactionInterface'

class TransactionService {
  createTransaction(payload: UnwrapNestedRefs<TransactionInterface>): AxiosPromise<TransactionResponseInterface> {
    return ApiService.post('/transaction', payload)
  }

  getTransactions(): AxiosPromise<TransactionResponseInterface> {
    return ApiService.get('/transaction')
  }

  getTransaction(id: number): AxiosPromise<TransactionResponseInterface> {
    return ApiService.get(`/transaction/${id}`)
  }

  updateTransaction(payload: UnwrapNestedRefs<TransactionInterface>): AxiosPromise<TransactionResponseInterface> {
    return ApiService.put(`/transaction/${payload.id}`, payload)
  }

  deleteTransaction(id: number): AxiosPromise<TransactionResponseInterface> {
    return ApiService.delete(`/transaction/${id}`)
  }
}

export default new TransactionService()

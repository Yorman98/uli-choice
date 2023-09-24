import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue'
import ApiService from '@/services'
import type { BudgetResponseInterface } from '@/services/types/BudgetTypes'
import type { BudgetInterface } from '@/store/types/BudgetInterface'

class BudgetService {
  getBudgets(): AxiosPromise<BudgetResponseInterface> {
    return ApiService.get('/budgets')
  }

  getBudgetById(id: number): AxiosPromise<BudgetResponseInterface> {
    return ApiService.get(`/budgets/${id}`)
  }

  createBudget(payload: UnwrapNestedRefs<BudgetInterface>): AxiosPromise<BudgetResponseInterface> {
    return ApiService.post('/budgets', payload)
  }

  updateBudget(payload: UnwrapNestedRefs<BudgetInterface>): AxiosPromise<BudgetResponseInterface> {
    return ApiService.put(`/budgets/${payload.id}`, payload)
  }

  deleteBudget(id: number): AxiosPromise<BudgetResponseInterface> {
    return ApiService.delete(`/budgets/${id}`)
  }
}

export default new BudgetService()

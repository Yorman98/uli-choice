import type { BudgetInterface } from '@/store/types/BudgetInterface'

export interface BudgetResponseInterface {
  success: boolean
  error?: never
  budgets?: {
    current_page?: number
    data: BudgetInterface | BudgetInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}

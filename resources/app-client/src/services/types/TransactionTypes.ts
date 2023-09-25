import type { TransactionInterface } from '@/store/types/TransactionInterface'

export interface TransactionResponseInterface {
  success: boolean
  error?: never
  data?: {
    current_page?: number
    data: TransactionInterface | TransactionInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}

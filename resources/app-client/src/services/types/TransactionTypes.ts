import type { TransactionInterface } from '@/store/types/TransactionInterface'

export interface TransactionResponseInterface {
  success: boolean
  error?: never
  transaction?: TransactionInterface | TransactionInterface[]
  total_paid?: number
  total_pending?: number
}

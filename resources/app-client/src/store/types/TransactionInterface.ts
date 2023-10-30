import type { PaymentMethodInterface } from './PaymentMethodInterface'

export interface TransactionInterface {
  id?: number
  order_id?: number
  payment_method_id: number
  amount: number
  notes: string
  created_at?: string
  updated_at?: string
  payment_method?: PaymentMethodInterface
}

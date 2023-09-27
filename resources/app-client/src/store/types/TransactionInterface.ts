export interface TransactionInterface {
  id?: number
  payment_method_id: number
  amount: number
  notes?: string
  created_at?: string
  updated_at?: string
}

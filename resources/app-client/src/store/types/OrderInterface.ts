export interface OrderInterface {
  id?: number
  reference: string
  status_id: number
  cart_id: number
  total_price: number
  total_cost: number
  created_at?: string
  updated_at?: string
}

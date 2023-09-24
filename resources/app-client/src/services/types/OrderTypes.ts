import type { OrderInterface } from '@/store/types/OrderInterface'

export interface OrderResponseInterface {
  success: boolean
  error?: never
  order_id?: number
  data?: OrderInterface
}

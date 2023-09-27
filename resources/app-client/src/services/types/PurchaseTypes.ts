import type { PurchaseInterface } from '@/store/types/PurchaseInterface'

export interface PurchaseResponseInterface {
  success: boolean
  error?: never
  data?: {
    current_page?: number
    data: PurchaseInterface | PurchaseInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}

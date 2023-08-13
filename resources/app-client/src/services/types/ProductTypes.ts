import type { ProductInterface } from '@/store/types/ProductInterface'

export interface ProductResponseInterface {
  success: boolean
  error?: never
  data?: {
    current_page?: number
    data: ProductInterface | ProductInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}
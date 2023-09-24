import type { ProductInterface } from '@/store/types/ProductInterface'

export interface ProductResponseInterface {
  success: boolean
  product_id?: number
  error?: never
  data?: {
    current_page?: number
    data: ProductInterface | ProductInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}

export interface ProductCartResponseInterface {
  success: boolean
  data?: {
    current_page?: number
    last_page?: number
    per_page?: number
    total: number
    data: ProductInterface | ProductInterface[]
  }
}

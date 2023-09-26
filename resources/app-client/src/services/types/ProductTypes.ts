import type { ProductCartInterface, ProductInterface } from '@/store/types/ProductInterface'
import type { VariationInterface } from '@/store/types/VariationInterface'

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

export interface CartResponseInterface {
  success: boolean
  error?: never
  cart_id?: number
  products?: ProductCartInterface | ProductCartInterface[]
  total_price?: number
}

export interface VariationResponseInterface {
  success: boolean
  error?: never
  product_id?: number
  variations?: VariationInterface[]
}

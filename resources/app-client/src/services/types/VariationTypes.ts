import type { VariationInterface } from '@/store/types/VariationInterface'

export interface VariationResponseInterface {
  success: boolean
  error?: never
  product_id?: number
  variations?: VariationInterface[]
}

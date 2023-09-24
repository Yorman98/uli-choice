import type { VariationInterface } from './VariationInterface'

export interface ProductInterface {
  id: number
  name: string
  slug: string
  code: string
  description?: string
  image: string
  created_at?: string
  updated_at?: string
  deleted_at?: string
  categories: []
  variations: VariationInterface[]
}

export interface productCartInterface {
  product_id: number
  variation_id: number
  quantity: number
}

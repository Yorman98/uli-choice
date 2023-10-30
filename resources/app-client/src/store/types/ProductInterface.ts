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

export interface ProductCartRequestInterface {
  user_id: number
  product_id: number
  variation_id: number | null
  quantity: number
}

export interface ProductCartInterface {
  id: number
  image: string
  name: string
  product_id: number
  quantity: number
  unit_cost: number
  unit_price: number
  variation_id: number
}

import type { ProductLinkInterface } from '@/store/types/ProductLinkInterface'

export interface BudgetInterface {
  id?: number
  user_id: number
  user_full_name?: string
  status_id: number
  price: number
  cost: number
  product_links: ProductLinkInterface[]
  created_at?: string
  updated_at?: string
}

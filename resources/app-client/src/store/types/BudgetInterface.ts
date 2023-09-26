import type { ProductLinkInterface } from '@/store/types/ProductLinkInterface'

export interface BudgetInterface {
  id?: number
  user_id: number
  user_full_name?: string
  status_id: number
  user?: UserOfBudget[]
  status?: {
    name: string
  }
  statusName?: string
  price: number
  cost: number
  product_links: ProductLinkInterface[]
  created_at?: string
  updated_at?: string
}

interface UserOfBudget {
  first_name: string
  last_name: string
  id: number
}

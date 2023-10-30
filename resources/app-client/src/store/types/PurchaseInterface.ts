import type { ProviderInterface } from '@/store/types/ProviderInterface'

export interface PurchaseInterface {
  id?: number
  total: number
  description?: string
  provider_id: number
  created_at?: string
  updated_at?: string
  provider?: ProviderInterface | string
}

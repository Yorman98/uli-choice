import type { ProviderInterface } from '@/store/types/ProviderInterface'

export interface ProviderResponseInterface {
  success: boolean
  error?: never
  providers?: {
    current_page?: number
    data: ProviderInterface | ProviderInterface[]
    last_page?: number
    per_page?: number
    total?: number
  }
}

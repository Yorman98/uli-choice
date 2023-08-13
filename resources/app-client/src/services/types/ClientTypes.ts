import type { ClientInterface } from '@/store/types/ClientInterface'

export interface ClientResponseInterface {
  success: boolean
  error?: never
  data?: ClientInterface | ClientInterface[]
}

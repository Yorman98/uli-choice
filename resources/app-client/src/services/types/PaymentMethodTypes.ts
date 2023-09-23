import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'

export interface PaymentMethodResponseInterface {
  success: boolean
  error?: never
  data?: PaymentMethodInterface | PaymentMethodInterface[]
}

import type { UserResponseInterface } from '@/services/types/UserTypes'

export interface LoginResponseInterface {
  success: boolean
  authorization?: {
    token: string
    type: string
  }
  error?: never
  message?: string
}

export interface RegisterResponseInterface {
  success: boolean
  errors?: never
  user?: UserResponseInterface
}

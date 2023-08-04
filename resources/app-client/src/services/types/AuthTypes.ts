export interface LoginResponseInterface {
  success: boolean
  authorization?: {
    token: string
    type: string
  }
  error?: never
  message?: string
}

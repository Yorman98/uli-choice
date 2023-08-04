import type { AxiosPromise } from 'axios'
import ApiService from '@/services'
import type { LoginResponseInterface, RegisterResponseInterface } from '@/services/types/AuthTypes'

const prefix = '/auth'

class AuthService {
  login(payload: { email: string; password: string }): AxiosPromise<LoginResponseInterface> {
    return ApiService.post(`${prefix}/login`, payload)
  }

  register(payload: { first_name: string; last_name: string; phone_number: string; email: string; password: string }): AxiosPromise<RegisterResponseInterface> {
    return ApiService.post(`${prefix}/register`, payload)
  }

  logout(): AxiosPromise {
    return ApiService.delete(`${prefix}/logout`)
  }
}

export default new AuthService()

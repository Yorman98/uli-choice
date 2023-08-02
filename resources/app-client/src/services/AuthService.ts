import type { AxiosPromise } from 'axios'
import ApiService from '@/services'
import type { LoginResponseInterface } from '@/services/types/AuthTypes'

const prefix = '/auth'

class AuthService {
  login(payload: { email: string; password: string }): AxiosPromise<LoginResponseInterface> {
    return ApiService.post(`${prefix}/login`, payload)
  }

  logout(): AxiosPromise {
    return ApiService.delete(`${prefix}/logout`)
  }
}

export default new AuthService()

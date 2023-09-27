import type { AxiosPromise } from 'axios'
import ApiService from '@/services'
import type { UserResponseInterface } from '@/services/types/UserTypes'

class UserService {
  getUser(): AxiosPromise<UserResponseInterface> {
    return ApiService.get('/user')
  }
}

export default new UserService()

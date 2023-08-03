import { defineStore } from 'pinia'
import type { AxiosResponse } from 'axios'
import type { UserInterface } from '@/store/interfaces/UserInterface'
import AuthService from '@/services/AuthService'
import UserService from '@/services/UserService'
import type { LoginResponseInterface } from '@/services/types/AuthTypes'
import type { UserResponseInterface } from '@/services/types/UserTypes'

export const useUserStore = defineStore('user', {
  state: () => {
    return {
      userInfo: {
        firstName: '',
        lastName: '',
        email: '',
        role: '',
      } as UserInterface,
      accessToken: '' as string | undefined,
    }
  },
  getters: {
    getAccessToken: state => state.accessToken ? state.accessToken : localStorage.getItem('accessToken'),
    getUserInfo: state => state.userInfo,
  },
  actions: {
    async login(payload: { email: string; password: string }) {
      const response: AxiosResponse<LoginResponseInterface> = await AuthService.login(payload)

      this.accessToken = response.data?.authorization?.token

      if (this.accessToken)
        localStorage.setItem('accessToken', this.accessToken)
    },
    async loadUser() {
      const response: AxiosResponse<UserResponseInterface> = await UserService.getUser()

      this.userInfo = {
        firstName: response.data.first_name,
        lastName: response.data.last_name,
        email: response.data.email,
        role: response.data.role,
      }
    },
  },
})

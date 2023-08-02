import { defineStore } from 'pinia'
import type { AxiosResponse } from 'axios'
import type { UserInterface } from '@/store/interfaces/UserInterface'
import AuthService from '@/services/AuthService'
import type { LoginResponseInterface } from '@/services/types/AuthTypes'

export const useUserStore = defineStore('user', {
  state: () => {
    return {
      userInfo: {
        firstName: '',
        lastName: '',
        email: '',
        userType: '',
      } as UserInterface,
      accessToken: '' as string | undefined,
    }
  },
  getters: {
    getAccessToken: state => state.accessToken,
    getUserInfo: state => state.userInfo,
  },
  actions: {
    async login(payload: { email: string; password: string }) {
      const response: AxiosResponse<LoginResponseInterface> = await AuthService.login(payload)

      this.accessToken = response.data?.authorization?.token
    },
  },
})

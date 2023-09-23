import { defineStore } from 'pinia'
import type { AxiosResponse } from 'axios'
import type { UserInterface } from '@/store/types/UserInterface'
import UserService from '@/services/UserService'
import AuthService from '@/services/AuthService'
import type { LoginResponseInterface, RegisterResponseInterface } from '@/services/types/AuthTypes'
import type { UserResponseInterface } from '@/services/types/UserTypes'
import { VariantInterface } from "@/store/types/VariantInterface";
import { ProductInterface } from "@/store/types/ProductInterface";
import { CategoryInterface } from "@/store/types/CategoryInterface";

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
      productsCart: []
    }
  },
  getters: {
    getAccessToken: state => state.accessToken ? state.accessToken : localStorage.getItem('accessToken'),
    getUserInfo: state => state.userInfo,
    getProductsCart: state => state.productsCart,
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
    async register(payload: { first_name: string; last_name: string; phone_number: string; email: string; password: string }) {
      const response: AxiosResponse<RegisterResponseInterface> = await AuthService.register(payload)

      if (response.data.user) {
        this.userInfo = {
          firstName: response.data.user?.first_name,
          lastName: response.data.user?.last_name,
          email: response.data.user?.email,
          role: response.data.user?.role,
        }
      }
    },
    async addToCart(payload: { product: ProductInterface; quantity: number }) {
      if (this.productsCart.indexOf(payload.product) === -1) {
        this.productsCart.push(
          {
            product: payload.product,
            quantity: payload.quantity
          })
      } else {
        this.productsCart[this.productsCart.indexOf(payload)].quantity += payload.quantity
      }

    },
    async removeFromCart(payload: { product: ProductInterface; quantity: number }) {
      this.productsCart.splice(this.productsCart.indexOf(payload), 1)
    }
  },
})

import { defineStore } from 'pinia'
import type { AxiosResponse } from 'axios'
import type { UserInterface } from '@/store/types/UserInterface'
import UserService from '@/services/UserService'
import AuthService from '@/services/AuthService'
import type { LoginResponseInterface, RegisterResponseInterface } from '@/services/types/AuthTypes'
import type { UserResponseInterface } from '@/services/types/UserTypes'
import { VariantInterface } from "@/store/types/VariantInterface";
import { ProductInterface, ProductCartRequestInterface } from "@/store/types/ProductInterface";
import { CategoryInterface } from "@/store/types/CategoryInterface";
import ProductService from "@/services/ProductService";

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
      productsCart: [] as any,
      productsCartTotal: 0 as number,
      cartId: 0 as number
    }
  },
  getters: {
    getAccessToken: state => state.accessToken ? state.accessToken : localStorage.getItem('accessToken'),
    getUserInfo: state => state.userInfo,
    getProductsCart: state => state.productsCart,
    getCartId: state => state.cartId,
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
        id: response.data.id
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
    async addToCart(payload: ProductCartRequestInterface) {
      const response = await ProductService.addProductCart(payload)
      this.productsCart = response.data.products
      this.productsCartTotal = response.data.total_price
      return response.data.cart_id
    },
    async fetchProductsCart(userId: number) {
      const response = await ProductService.getProductsCart(userId)
      this.productsCart = response.data.products
      this.cartId = response.data.cart_id
      this.productsCartTotal = response.data.total_price
    },
    async removeFromCart(productCartId: number) {
      await ProductService.removeFromCart(productCartId)
    },
    async updateProductQuantity (payload: { productCartId: number, quantity: number }) {
      await ProductService.updateQuantity(payload)
    }
  },
})

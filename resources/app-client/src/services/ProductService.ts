import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue/dist/vue'
import ApiService from '@/services'
import type { ProductResponseInterface } from '@/services/types/ProductTypes'
import type { ProductInterface } from '@/store/types/ProductInterface'

class ProductService {
  createProduct(payload: UnwrapNestedRefs<ProductInterface>): AxiosPromise<ProductResponseInterface> {
    return ApiService.post('/product', payload,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  createProductVariant (payload: any): AxiosPromise<ProductResponseInterface> {
    return ApiService.post(`/product/${payload.productId}/variations`, payload.data,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  getProducts(): Promise<ProductResponseInterface> {
    return ApiService.get('/product')
  }

  getProduct(id: number): AxiosPromise<ProductResponseInterface> {
    return ApiService.get(`/product/${id}`)
  }

  updateProduct(id: number, payload: UnwrapNestedRefs<ProductInterface>): AxiosPromise<ProductResponseInterface> {
    return ApiService.post(`/product/${id}`, payload,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  deleteProduct(id: number): AxiosPromise<ProductResponseInterface> {
    return ApiService.delete(`/product/${id}`)
  }
}

export default new ProductService()

import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue/dist/vue'
import ApiService from '@/services'
import type { ProductResponseInterface, ProductCartResponseInterface } from '@/services/types/ProductTypes'
import type { ProductInterface, productCartInterface } from '@/store/types/ProductInterface'

class ProductService {
  createProduct(payload: UnwrapNestedRefs<ProductInterface>): AxiosPromise<ProductResponseInterface> {
    return ApiService.post('/product', payload,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  createProductVariant (payload: { productId: number, data: {} }): AxiosPromise<ProductResponseInterface> {
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

  getProductsCart (cart_id): AxiosPromise<ProductCartResponseInterface> {
    return ApiService.get(`/cart/${cart_id}`)
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

  addProductCart(payload: productCartInterface): AxiosPromise<ProductCartResponseInterface> {
    return ApiService.post(`/cart`, payload)
  }
}

export default new ProductService()

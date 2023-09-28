import type { AxiosPromise } from 'axios'
import type { UnwrapNestedRefs } from 'vue/dist/vue'
import ApiService from '@/services'
import type { CartResponseInterface, ProductResponseInterface, VariationResponseInterface } from '@/services/types/ProductTypes'
import type { ProductCartRequestInterface, ProductInterface } from '@/store/types/ProductInterface'

class ProductService {
  /* Products */
  createProduct(payload: UnwrapNestedRefs<ProductInterface>): AxiosPromise<ProductResponseInterface> {
    return ApiService.post('/product', payload,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  getProduct(id: number): AxiosPromise<ProductResponseInterface> {
    return ApiService.get(`/product/${id}`)
  }

  getProducts(): Promise<ProductResponseInterface> {
    return ApiService.get('/product')
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

  /* Products Variation */
  createProductVariant(payload: { productId: number; data: {} }): AxiosPromise<ProductResponseInterface> {
    return ApiService.post(`/product/${payload.productId}/variations`, payload.data,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
  }

  getVariationByProduct(productId: number): AxiosPromise<VariationResponseInterface> {
    return ApiService.get(`/product/${productId}/variations`)
  }

  /* Products card */
  getProductsActiveCart(userId: number): AxiosPromise<CartResponseInterface> {
    return ApiService.get(`/cart/${userId}`)
  }

  getProductsCart(cartId: number): AxiosPromise<CartResponseInterface> {
    return ApiService.get(`/cart/${cartId}/products`)
  }

  addProductCart(payload: UnwrapNestedRefs<ProductCartRequestInterface>): AxiosPromise<CartResponseInterface> {
    return ApiService.post('/cart', payload)
  }

  removeFromCart(productCartId: number): AxiosPromise<CartResponseInterface> {
    return ApiService.delete(`/cart/${productCartId}`)
  }

  updateQuantity(payload: { productCartId: number; quantity: number }): AxiosPromise<CartResponseInterface> {
    return ApiService.post('/cart/update-quantity', payload)
  }

  deleteVariantion(payload: { productId: number; variantId: number }): AxiosPromise<VariationResponseInterface> {
    return ApiService.delete(`/product/${payload.productId}/variations/${payload.variantId}`)
  }
}

export default new ProductService()

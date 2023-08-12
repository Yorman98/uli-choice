import ApiService from '@/services'
import type { ProductResponseInterface } from '@/services/types/ProductTypes'

class ProductService {
  getProducts(): Promise<ProductResponseInterface> {
    return ApiService.get('/product')
  }
}

export default new ProductService()

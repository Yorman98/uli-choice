import type { AxiosPromise } from 'axios'
import ApiService from '@/services'
import type { ProductResponseInterface } from '@/services/types/ProductTypes'

class ProductService {
    getProduct(product_id: number): AxiosPromise<ProductResponseInterface> {
        return ApiService.get(`/product/${product_id}`)
    }
}

export default new ProductService();
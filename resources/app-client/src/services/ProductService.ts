import type { AxiosPromise } from 'axios'
import ApiService from '@/services'

class CategoryService {
	createProduct(payload: any): AxiosPromise<any> {
    return ApiService.post('/product', payload,
      { headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  }

  getProducts(payload: any): AxiosPromise<any> {
    return ApiService.get(`/product/?page=${payload.page}&perPage=${payload.perPage}`)
  }

  getProduct(id: number): AxiosPromise<any> {
    return ApiService.get(`/product/${id}`)
  }

  updateProduct(id: number, payload: any): AxiosPromise<any> {
    return ApiService.post(`/product/${id}`, payload,
    { headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  }

  deleteProduct(id: number): AxiosPromise<any> {
    return ApiService.delete(`/product/${id}`)
  }
}

export default new CategoryService()

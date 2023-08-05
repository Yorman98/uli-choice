import type { AxiosPromise } from 'axios'
import CategoriesResponseInterface from '@/services/types/CategoriesTypes'
import ApiService from '@/services'

class CategoryService {
  createCategory(payload): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.post('/category', payload)
  }

  getCategories(): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.get('/category')
  }

  getCategory(id): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.get(`/category/${id}`)
  }

  updateCategory(payload): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.put(`/category/${payload.id}`, payload)
  }

  deleteCategory(id): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.delete(`/category/${id}`)
  }
}

export default new CategoryService()

import type { AxiosPromise } from 'axios'
import CategoriesResponseInterface from '@/services/types/CategoriesTypes'
import ApiService from '@/services'
import { CategoryInterface } from "@/store/types/CategoryInterface";
class CategoryService {
  createCategory(payload: CategoryInterface): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.post('/category', payload)
  }

  getCategories(): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.get('/category')
  }

  getCategory(id: number): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.get(`/category/${id}`)
  }

  updateCategory(payload: CategoryInterface): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.put(`/category/${payload.id}`, payload)
  }

  deleteCategory(id: number): AxiosPromise<CategoriesResponseInterface> {
    return ApiService.delete(`/category/${id}`)
  }
}

export default new CategoryService()

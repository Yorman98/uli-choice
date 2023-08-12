import { CategoryInterface } from '@/store/types/CategoryInterface'

export interface CategoriesResponseInterface {
  success: boolean
  categories: CategoryInterface
  error?: never
  message?: string
}

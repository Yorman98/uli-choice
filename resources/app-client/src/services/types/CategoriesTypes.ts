import { CategoryInterface } from "./CategoryInterface";
export interface CategoriesResponseInterface {
  current_page: number,
  data: CategoryInterface[],
  last_page: number,
  per_page: number,
  total: number
}

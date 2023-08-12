export interface ProductInterface {
  id?: number
  name: string
  slug: string
  code: string
  description: string
  groups: string[]
  attributes: string[]
  image: string
  created_at?: string
  updated_at?: string
  deleted_at?: string
}

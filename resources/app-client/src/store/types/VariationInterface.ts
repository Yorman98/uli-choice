export interface VariationInterface {
  sku: string
  price: number
  cost: number
  stock: number
  group?: number
  image?: string
  attributes: number[]
}

export interface VariationSelectInterface {
  id: number
  attributes: string
}

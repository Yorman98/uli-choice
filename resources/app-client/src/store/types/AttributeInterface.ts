export interface AttributeGroupInterface {
  id?: number
  name: string
	group_type: string
  attributes: AttributeInterface[]
  updated_at?: string
  created_at?: string
}

export interface AttributeInterface {
  id: number
  name: string
  attribute_group_id?: number
  updated_at?: string
  created_at?: string
}

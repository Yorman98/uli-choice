export interface AttributeGroupInterface {
  id?: number
  name: string
	group_type: string
}

export interface AttributeInterface {
  id?: number
  name: string
  attributeGroupId?: number
}

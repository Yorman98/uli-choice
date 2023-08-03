export interface AttributeGroupInterface {
  id: number
  name: string
  groupType: string
}

export interface AttributeInterface {
  id: number
  name: string
  attributeGroupId: number
}

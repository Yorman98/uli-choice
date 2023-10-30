import type { AxiosPromise } from 'axios'
import ApiService from '@/services'
import { AttributeGroupResponseInterface } from "@/services/types/AttributeGroupTypes";
import { AttributeGroupInterface } from "@/store/types/AttributeInterface";
import { AttributeInterface } from "@/store/types/AttributeInterface";
import { AttributeResponseResponse } from "@/services/types/AttributeTypes";

class CategoryService {
  createAttributeGroup(payload: AttributeGroupInterface): AxiosPromise<AttributeGroupResponseInterface> {
    return ApiService.post('/attribute-group', payload)
  }

  getAttributeGroup(): AxiosPromise<AttributeGroupResponseInterface> {
    return ApiService.get('/attribute-group')
  }

  getAttributesGroupById(id: number): AxiosPromise<AttributeGroupResponseInterface> {
    return ApiService.get(`/attribute-group/${id}`)
  }

  updateAttributeGroup(payload: AttributeGroupInterface): AxiosPromise<AttributeGroupResponseInterface> {
    return ApiService.put(`/attribute-group/${payload.id}`, payload)
  }

  deleteAttributeGroup(id: number): AxiosPromise<getAttributesGroupById> {
    return ApiService.delete(`/attribute-group/${id}`)
  }

  createAttribute(payload: AttributeInterface): AxiosPromise<any> {
    return ApiService.post('/attribute', payload)
  }

  getAttribute(id: number): AxiosPromise<any> {
    return ApiService.get(`/attribute/${id}`)
  }

  updateAttribute(payload: AttributeInterface): AxiosPromise<AttributeResponseResponse> {
    return ApiService.put(`/attribute/${payload.id}`, payload)
  }

  deleteAttribute(id: number): AxiosPromise<AttributeResponseResponse> {
    return ApiService.delete(`/attribute/${id}`)
  }
}

export default new CategoryService()

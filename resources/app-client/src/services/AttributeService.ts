import type { AxiosPromise } from 'axios'
import ApiService from '@/services'

class CategoryService {
  createAttributeGroup(payload): AxiosPromise<any> {
    return ApiService.post('/attribute-group', payload)
  }

  getAttributeGroup(): AxiosPromise<any> {
    return ApiService.get('/attribute-group')
  }

  getAttributesGroupById(id): AxiosPromise<any> {
    return ApiService.get(`/attribute-group/${id}`)
  }

  updateAttributeGroup(payload): AxiosPromise<any> {
    return ApiService.put(`/attribute-group/${payload.id}`, payload)
  }

  deleteAttributeGroup(id): AxiosPromise<any> {
    return ApiService.delete(`/attribute-group/${id}`)
  }

  createAttribute(payload): AxiosPromise<any> {
    return ApiService.post('/attribute', payload)
  }

  getAttribute(id): AxiosPromise<any> {
    return ApiService.get(`/attribute/${id}`)
  }

  updateAttribute(payload): AxiosPromise<any> {
    return ApiService.put(`/attribute/${payload.id}`, payload)
  }

  deleteAttribute(id): AxiosPromise<any> {
    return ApiService.delete(`/attribute/${id}`)
  }
}

export default new CategoryService()

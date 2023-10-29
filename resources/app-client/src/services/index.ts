import type { AxiosInstance } from 'axios'
import axios from 'axios'
import router from '@/router'

const apiClient: AxiosInstance = axios.create({
  // TODO: Create env var
  baseURL: process.env.APIURL,
  headers: {
    'Content-type': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('accessToken')}`,
  },
})

apiClient.interceptors.request.use(config => {
  if (localStorage.getItem('accessToken')) {
    config.headers!.Authorization = `Bearer ${localStorage.getItem('accessToken')}`
  }
  return config
})
apiClient.interceptors.response.use(null, error => {
  if (error.response.status === 401)
    router.push('/login')
  else if (error.response.status === 404)
    router.push('/404')

  return Promise.reject(error)
})

export default apiClient

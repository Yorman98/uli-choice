import type { AxiosInstance } from 'axios'
import axios from 'axios'

const apiClient: AxiosInstance = axios.create({
  // TODO: Create env var
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-type': 'application/json',
  },
})

export default apiClient

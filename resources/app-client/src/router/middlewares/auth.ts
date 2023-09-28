import { useUserStore } from '@/store/user'
import { ADMIN, CLIENT } from '@/utils/constants'
import { useRouter } from 'vue-router'

export function authPublic({ next }: any) {
  return next()
}

export function authAdmin({ next }: any) {
  const userStore = useUserStore()
  const user = userStore.$state
  const router = useRouter()

  // console.log(user.accessToken)
  // if (userStore.userInfo.role !== '' && (!user || user.accessToken === '')) {
  //   router.replace({ name: 'login' })
  // }

  if (userStore.userInfo.role !== '' && userStore.userInfo.role !== ADMIN) {
    return next({
      name: 'error'
    })
  }

  return next()
}

export function authClient({ next }: any) {
  const userStore = useUserStore()
  const user = userStore.$state
  const router = useRouter()

  // if (userStore.userInfo.role !== '' && (!user || user.accessToken === '')) {
  //   router.replace({ name: 'login' })
  // }

  if (userStore.userInfo.role !== '' && userStore.userInfo.role !== CLIENT) {
    return next({
      name: 'error'
    })
  }

  return next()
}

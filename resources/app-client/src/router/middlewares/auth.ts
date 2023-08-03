import { useUserStore } from '@/store/user'

export function authAdmin({ next, router }: any) {
  const userStore = useUserStore()
  const user = userStore.$state

  if (!user || user.accessToken === '')
    router.replace({ name: 'login' })

  if (user.userInfo.role !== 'admin')
    router.replace({ to: '/404' })

  return next()
}

export function authClient({ next, router }: any) {
  const userStore = useUserStore()
  const user = userStore.$state

  if (!user || user.accessToken === '')
    router.replace({ name: 'login' })

  return next()
}

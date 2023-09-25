<script setup lang="ts">
import { useTheme } from 'vuetify'

import { hexToRgb } from '@layouts/utils'
import { useUserStore } from '@/store/user'

const { global } = useTheme()
import ProductService from '@/services/ProductService'
const userStore = useUserStore()



onBeforeMount(async () => {
  await userStore.loadUser()
  await userStore.fetchProductsCart(userStore.getUserInfo.id)
})
</script>

<template>
  <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
    <RouterView />
  </VApp>
</template>

<script setup lang="ts">
import { useTheme } from 'vuetify'

import { hexToRgb } from '@layouts/utils'
import { useUserStore } from '@/store/user'

const { global } = useTheme()
const userStore = useUserStore()



onBeforeMount(async () => {
  await userStore.loadUser()
})
</script>

<template>
  <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
    <RouterView />
  </VApp>
</template>

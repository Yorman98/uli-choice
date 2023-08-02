<script setup lang="ts">
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import {useI18n} from "vue-i18n";

const { t } = useI18n()

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('products.categories.categories_group_title'),
    disabled: true,
  },
]
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        :title="$t('products.categories.categories_group_title')"
        :path="path"
      />
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">

</style>

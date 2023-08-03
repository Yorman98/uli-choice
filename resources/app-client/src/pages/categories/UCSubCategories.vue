<script setup lang="ts">
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import { useI18n } from 'vue-i18n'
import { CategoryInterface } from '@/store/types/CategoryInterface'
import { ref, Ref } from 'vue'
import { useRouter } from 'vue-router'

const { t } = useI18n()

const router = useRouter()

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

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.description'), key: 'description' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const isEdit: Ref<boolean> = ref(false)

const dialog: Ref<boolean> = ref(false)

const categories: CategoryInterface[] = [
  {
    id: 1,
    name: 'Mujer',
    slug: 'mujer',
    description: 'Ropa para mujer'
  },
  {
    id: 2,
    name: 'Hombre',
    slug: 'hombre',
    description: 'Ropa para hombre'
  },
  {
    id: 3,
    name: 'Niños',
    slug: 'niños',
    description: 'Ropa para niños'
  },
]

let category: CategoryInterface = {
  id: 0,
  name: '',
  slug: '',
  description: ''
}

function editItem(payload: CategoryInterface) {
  category = payload
  isEdit.value = true
  dialog.value = true
}

function goToCategory(payload: CategoryInterface) {
  router.push({
    name: 'subCategories',
    params: {
      category: payload.name.toLowerCase(),
    },
  })
}

function deleteItem(payload: number) {
  console.log(payload)
}

function closeDialog() {
  dialog.value = false
  isEdit.value = false
  category = {
    id: 0,
    name: '',
    slug: '',
    description: ''
  }
}

function saveCategoryData() {
  console.log(category)
  closeDialog()
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      test
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">

</style>

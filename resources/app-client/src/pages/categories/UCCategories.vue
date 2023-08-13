<script setup lang="ts">
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import { useI18n } from 'vue-i18n'
import { CategoryInterface } from '@/store/types/CategoryInterface'
import { ref, Ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import CategoryService from '@/services/CategoryService'

const { t } = useI18n()

const router = useRouter()

const route = useRoute()

const path: Ref<any[]> = ref([
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
])

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.description'), key: 'description' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const isEdit: Ref<boolean> = ref(false)

const dialog: Ref<boolean> = ref(false)

let categories: Ref<CategoryInterface[]> = ref([])

let category: CategoryInterface = reactive({
  name: '',
  slug: '',
  description: ''
})

watch(route, () => initData(route))

onMounted(async () => {
  const response = await CategoryService.getCategories()
  categories.value = response.data.categories.data
})

async function initData(route: any) {
  if (Object.keys(route.params).length > 0) {
    const response = await CategoryService.getCategory(route.params.category)
    categories.value = response.data.categories.data
    path.value.push({
      title: route.params.category,
      disabled: true,
    })
  } else {
    const response = await CategoryService.getCategories()
    categories.value = response.data.categories.data
  }
}

function editItem(payload: CategoryInterface) {
  Object.assign(category, payload)
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

async function deleteItem(payload: number) {
  await CategoryService.deleteCategory(payload)
  await initData(route)
}

function closeDialog() {
  dialog.value = false
  isEdit.value = false
  Object.assign(category, {
    name: '',
    slug: '',
    description: ''
  })
}

async function saveCategoryData() {
  if (isEdit.value) {
    await CategoryService.updateCategory(category)
  } else {
    await CategoryService.createCategory(category)
  }
  await initData(route)
  closeDialog()
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('products.categories.categories_group_title')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="dialog = true">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0">
              {{ t('products.categories.create_category') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="categories"
            :hasSubItems="false"
            @editItem="editItem"
            @goToItem="goToCategory"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="dialog"
        max-width="700px"
        >
          <VCard class="pa-4">
            <VCardTitle color="red">
              <h4 class="text-h4 mb-4 white--text">
                {{ isEdit ? $t('products.categories.edit_category') : $t('products.categories.create_category') }}
              </h4>
            </VCardTitle>

            <VCardText>
              <VTextField
                v-model="category.name"
                class="mb-8"
                :placeholder="$t('products.categories.category_name')"
                :label="$t('products.categories.category_name')"
              />

              <VTextField
                v-model="category.slug"
                class="mb-8"
                :placeholder="$t('products.categories.category_slug')"
                :label="$t('products.categories.category_slug')"
              />

              <VTextField
                v-model="category.description"
                :placeholder="$t('products.categories.category_description')"
                :label="$t('products.categories.category_description')"
              />
            </VCardText>

            <VCardActions class="d-flex justify-end">
                <VBtn
                  color="primary"
                  outlined
                  @click="closeDialog"
                >
                  {{ $t('global.cancel') }}
                </VBtn>

                <VBtn
                  color="primary"
                  flat
                  @click="saveCategoryData"
                >
                  {{ isEdit ? $t('global.update') : $t('global.save') }}
                </VBtn>
            </VCardActions>
          </VCard>
        </VDialog>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
.v-btn {
  span.v-btn__content p {
    color: #FFFFFF;
  }
}
</style>

<script setup lang="ts">
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import { useI18n } from 'vue-i18n'
import { CategoryInterface } from '@/store/types/CategoryInterface'
import { ref, Ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

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

let category: CategoryInterface = {
  id: 0,
  name: '',
  slug: '',
  description: ''
}

watch(route, () => initData(route))

onMounted(() => {
  categories.value = [
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
})

function initData(route: any) {
  if (Object.keys(route.params).length > 0) {
    categories.value = [
      {
        id: 1,
        name: 'Panatalones',
        slug: 'mujer',
        description: 'Panatalones para mujer'
      },
      {
        id: 2,
        name: 'Faldas',
        slug: 'mujer',
        description: 'faldas para mujer'
      },
      {
        id: 3,
        name: 'Sombreros',
        slug: 'mujer',
        description: 'Sombreros para mujer'
      },
    ]
    path.value.push({
      title: route.params.category,
      disabled: true,
    })
  } else {
    categories.value = [
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
  }
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
            :hasSubItems="true"
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

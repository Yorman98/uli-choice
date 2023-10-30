<script setup lang="ts">
import type { Ref } from 'vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import type { ProductInterface } from '@/store/types/ProductInterface'
import ProductService from '@/services/ProductService'
import router from '@/router'
import {useUserStore} from "@/store/user";

const userStore = useUserStore()

const { t } = useI18n()

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('products.products_list'),
    disabled: true,
  },
])

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.description'), key: 'description' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const productsList: Ref<ProductInterface[]> = ref([])

async function dataProducts() {
  const response = await ProductService.getProducts()
  const data = response?.data?.data ?? []

  data.data.forEach((product: ProductInterface) => {
    productsList.value.push(product)
  })
}

function addProduct() {
  router.push({
    name: 'formProduct',
  })
}

async function exportProducts() {
  const response = await ProductService.exportProducts()
  window.location
}

function viewProduct(product: ProductInterface) {
  router.push({
    name: 'product',
    params: {
      id: product.id,
    },
  });
}

function editProduct(product: ProductInterface) {
  router.push({
    name: 'editFormProduct',
    params: {
      id: product.id,
    },
  })
}

async function deleteProduct(productId: number) {
  await ProductService.deleteProduct(productId)
  productsList.value = []
  await dataProducts()
}

onMounted(async () => {
  await dataProducts()
})

async function addToCart(product: ProductInterface) {
  const response = await userStore.addToCart(
    {
      product_id: 27,
      variation_id: 12,
      quantity: 3,
    }
  )

  await ProductService.getProductsActiveCart(response)
}
</script>

<template>
  <VRow class="uc-products-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.products')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="addProduct" class="mr-4">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0 text-white">
              {{ t('products.add_product') }}
            </p>
          </VBtn>

        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="productsList"
            only-edit
            @goToItem="viewProduct"
            @editItem="editProduct"
            @deleteItem="deleteProduct"
          />
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

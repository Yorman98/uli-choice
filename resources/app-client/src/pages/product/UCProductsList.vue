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

//************** test **************//
const products = [
  {
    id: 1,
    name: 'Producto 1',
    slug: 'Producto-1',
    code: 'cod001',
    description: 'descripcion 1',
    image: 'https://picsum.photos/200/300',
    categories: [],
    variations: [],
  },
  {
    id: 2,
    name: 'Producto 2',
    slug: 'Producto-2',
    code: 'cod002',
    description: 'descripcion 2',
    image: 'https://picsum.photos/200/300',
    categories: [],
    variations: [],
  },
  {
    id: 3,
    name: 'Producto 3',
    slug: 'Producto-3',
    code: 'cod003',
    description: 'descripcion 3',
    image: 'https://picsum.photos/200/300',
    categories: [],
    variations: [],
  }
];

const userStore = useUserStore()
//************** test **************//

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

  await ProductService.getProductsCart(response)
}
</script>

<template>
  <VRow class="uc-products-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.purchases')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="addProduct">
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
            has-sub-items
            @goToItem="viewProduct"
            @editItem="editProduct"
            @deleteItem="deleteProduct"
          />
        </VCardText>
      </VCard>

      <VCard class="pa-4">
        <VCardText class="d-flex">
          <div
            v-for="(product, index) in products"
            :key="index"
            class="mx-4"
          >
            <div>
              <p>{{ product.name }}</p>
              <p>{{ product.description }}</p>
            </div>

            <VBtn @click="addToCart(product)">
              add to cart
            </VBtn>
          </div>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<script setup lang="ts">
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { ProductInterface } from '@/store/types/ProductInterface'
import ProductService from '@/services/ProductService'

const productsList: Ref<ProductInterface[]> = ref([])

async function dataProducts() {
  const response = await ProductService.getProducts()
  const data = response?.data?.data ?? []

  data.data.forEach((product: ProductInterface) => {
    productsList.value.push(product)
  })
}

onMounted(async () => {
  await dataProducts()
})
</script>

<template>
  <VRow class="uc-products-container">
    <VCol cols="12">
      <div
        v-for="product in productsList"
        :key="product.id"
      >
        {{ product.image }}
        {{ product.name }}
      </div>
    </VCol>
  </VRow>
</template>

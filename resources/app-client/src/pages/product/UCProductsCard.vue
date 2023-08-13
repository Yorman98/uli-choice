<script setup lang="ts">
import type { Ref } from 'vue'
import { ref } from 'vue'
import type { ProductInterface } from '@/store/types/ProductInterface'
import ProductService from '@/services/ProductService'
import { STORAGE_PATH } from '@/utils/constants'
import DemoFormLayoutHorizontalForm from "@/views/pages/form-layouts/DemoFormLayoutHorizontalForm.vue";

const productsList: Ref<ProductInterface[]> = ref([])

async function dataProducts() {
  const response = await ProductService.getProducts()
  const data = response?.data?.data ?? []

  data.data.forEach((product: ProductInterface) => {
    productsList.value.push(product)
  })
}

function getProductUrl(slug: string) {
  return `/product/${slug}`
}

onMounted(async () => {
  await dataProducts()
})
</script>

<template>
  <VRow class="uc-products-container ma-5">
    <VCol cols="12">
      <div
        v-for="product in productsList"
        :key="product.id"
      >
        <a
          class="cursor-pointer"
          :href="getProductUrl(product.slug)"
        >
          <VCard
            class="mx-auto"
            max-width="350"
          >
            <VImg
              :src="STORAGE_PATH + product.image"
              :alt="product.name"
              cover="true"
              height="300px"
            />
            <VCardText>
              <h5 class="text-h5">{{ product.name }}</h5>
            </VCardText>
          </VCard>
        </a>
      </div>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
.uc-products-container {
  .v-col {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px 10px;
  }

  .v-img {
    background: lightgrey;
  }
}
</style>

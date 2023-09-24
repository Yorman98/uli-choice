<script setup lang="ts">
import { defineExpose, ref } from 'vue'
import type { Ref } from 'vue'
import ProductService from '@/services/ProductService'
import type { ProductCartInterface } from '@/store/types/ProductInterface'
import { STORAGE_PATH } from '@/utils/constants'
import imageUrl from "@images/product/product-img.png";

const productsCartList: Ref<ProductCartInterface[]> = ref([])
async function updateCart(clientId: number) {
  if (!clientId)
    return

  const { data } = await ProductService.getProductsCart(clientId)

  productsCartList.value = data?.products ?? []
}

defineExpose({
  updateCart,
})
</script>

<template>
  <VCard
    class="mb-6 admin-cart-container"
    :title="$t('cart.details_cart')"
  >
    <VCardTitle class="d-flex justify-end mb-4">
      <VRow>
        <VCol
          cols="12"
          class="d-flex justify-end flex-column"
        >
          <ul>
            <li
              v-for="product in productsCartList"
              :key="product.id"
            >
              <div>
                <VImg
                  :src="`${STORAGE_PATH}/${product.image}`"
                  max-width="150"
                  height="150"
                  contain
                />
              </div>

              <div>
                <span>{{ product.name }}</span>
                <span>{{ product.quantity }}</span>
              </div>
            </li>
          </ul>
        </VCol>
      </VRow>
    </VCardTitle>
  </VCard>
</template>

<style lang="scss" scoped>
.admin-cart-container {
  ul {
    list-style: none;
  }
}
</style>

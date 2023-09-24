<script setup lang="ts">
import { useUserStore } from '@/store/user'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import type { Ref } from 'vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { STORAGE_PATH } from '@/utils/constants'


const cartStore = useUserStore()

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
    title: t('cart.my_product_list'),
    disabled: true,
  },
])

async function decreaseProduct (quantity: number, productId: number) {
  if (quantity > 0) {
    await cartStore.updateProductQuantity({id: productId, quantity: quantity})
    await cartStore.fetchProductsCart(cartStore.userInfo.id)
  }
}

async function increaseProduct (quantity: number, productId: number) {
  await cartStore.updateProductQuantity({ id: productId, quantity: quantity })
  await cartStore.fetchProductsCart(cartStore.userInfo.id)
}

async function removeProductCart (productId: number) {
  await cartStore.removeFromCart(productId)
  await cartStore.fetchProductsCart(cartStore.userInfo.id)
}
</script>

<template>
  <VRow class="cart-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('cart.my_product_list')"
        :path="path"
      />
    </VCol>

    <VCol cols="8">
      <VCard class="pa-0">
        <VCard-title class="px-0 py-2">
          <VRow class="header-cart pa-4">
            <VCol cols="6" class="pa-0">
              <p class="text-body-1 text-white text-center ma-0">{{ $t('cart.product') }}</p>
            </VCol>
            <VCol cols="3" class="pa-0">
              <p class="text-body-1 text-white ma-0">{{ $t('cart.price') }}</p>
            </VCol>
            <VCol cols="3" class="pa-0">
              <p class="text-body-1 text-white ma-0">{{ $t('cart.options') }}</p>
            </VCol>
          </VRow>
        </VCard-title>
        <VCardText class="py-4 px-0">
          <VRow v-for="(product, index) in cartStore.productsCart" class="product-data px-4" :key="index">
            <VCol cols="3" class="image-container">
              <img :src="`${STORAGE_PATH}${product.image}`" width="100" height="100" alt="product-img"/>
            </VCol>
            <VCol cols="3" class="product-info d-flex flex-column justify-center">
              <p class="text-body-1 ma-0">{{ product.name }}</p>
              <p v-for="(attribute, index) in product.attributes" class="text-body-2 ma-0" :key="index">
                {{ attribute }}
              </p>
            </VCol>
            <VCol cols="2" class="product-price d-flex align-center">
              <p class="ma-0">
                {{ `${product.unit_price}$` }}
              </p>
            </VCol>
            <VCol cols="4" class="product-actions d-flex justify-end align-center">
              <VTextField
                v-model="product.quantity"
                class="product-quantity ma-0"
                prepend-icon="mdi-minus"
                append-icon="mdi-plus"
                @click:prepend="decreaseProduct(product.quantity - 1, product.id)"
                @click:append="increaseProduct(product.quantity + 1, product.id)"
                hide-details
              ></VTextField>

              <v-icon @click="removeProductCart(product.id)" color="error">
                mdi-trash
              </v-icon>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
    <VCol cols="4">
      <VCard class="pa-0">
        <VCard-title class="cart-summary">
          <p class="text-body-1 text-white ma-0">{{ $t('cart.summary') }}</p>
        </VCard-title>

        <VCard-text class="pa-4">
          <div class="d-flex justify-space-between">
            <p>{{ `${cartStore.productsCart.length} ${$t('cart.articles')}` }}</p>
            <p>{{ `${cartStore.productsCartTotal}$` }}</p>
          </div>
        </VCard-text>
        <VCard-actions>
          <VBtn
            color="primary"
            class="text-none w-100"
            variant="flat"
          >
            {{ $t('cart.generate_order') }}
          </VBtn>
        </VCard-actions>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
.cart-container {
  .v-card-title {
    .header-cart {
      background: rgb(var(--v-theme-primary));
    }

    &.cart-summary {
      background: rgb(var(--v-theme-primary));
    }
  }
  .product-actions {
    gap: 20px;

    .product-quantity {
      max-width: 125px;
    }
  }

  .v-row.product-data:nth-child(even) {
    background: #696cff0f;
  }

  .product-quantity input {
    text-align: center;
  }
}

</style>

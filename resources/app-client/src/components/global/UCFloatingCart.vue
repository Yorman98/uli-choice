<script setup lang="ts">
import type { Ref } from 'vue'
import { useUserStore } from '@/store/user'
import { STORAGE_PATH } from '@/utils/constants'
import { useRouter } from 'vue-router';
import OrderService from "@/services/OrderService";

const router = useRouter()

let menu: Ref<boolean> = ref(false)

const cartStore = useUserStore()

async function removeProductCart (productId: number) {
  await cartStore.removeFromCart(productId)
  await cartStore.fetchProductsCart(cartStore.userInfo.id)
}

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

async function updateCart() {
  await cartStore.fetchProductsCart(cartStore.userInfo.id);
}

async function generateOrder() {
  await OrderService.createOrder({
    cart_id: cartStore.cartId
  })
  await cartStore.fetchProductsCart(cartStore.userInfo.id)
}
</script>

<template>
  <div class="floating-cart-container text-center mr-4">
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
    >
      <template v-slot:activator="{ props }">
        <VIcon v-bind="props">
          mdi-cart-minus
        </VIcon>
      </template>

      <v-card min-width="400">
        <VCardTitle>
          <p class="text-white text-body-1 ma-0">{{ $t('cart.my_products') }}</p>
        </VCardTitle>
        <VCardText class="pa-4">
          <p v-if="cartStore.productsCart.length === 0" class="pa-1 pt-1">
            {{ $t('cart.no_products') }}
          </p>
          <v-virtual-scroll
            v-else
            :height="200"
            :items="cartStore.productsCart"
          >
            <template v-slot:default="{ item }">
              <div class="product-item d-flex justify-start align-center mb-4">
                <VIcon @click="removeProductCart(item.id)">
                  mdi-close
                </VIcon>
                <img :src="`${STORAGE_PATH}${item.image}`" width="35" height="35" class="rounded-circle" alt="product-img"/>
                <div class="d-flex justify-space-between align-center w-75">
                  <p class="ma-0">{{ item.name }}</p>
                  <VTextField
                    v-model="item.quantity"
                    class="ma-0 text-center"
                    prepend-icon="mdi-minus"
                    append-icon="mdi-plus"
                    @click:prepend="decreaseProduct(item.quantity - 1, item.id)"
                    @click:append="increaseProduct(item.quantity + 1, item.id)"
                  ></VTextField>
                </div>
              </div>

            </template>
          </v-virtual-scroll>
        </VCardText>
        <VCardActions v-if="cartStore.productsCart.length > 0" class="d-flex flex-column">
          <div>
            <VBtn
              color="primary"
              class="mb-4"
              @click="menu = false; router.push({ name: 'cartPage' })"
              variant="flat"
            >
              {{ $t('cart.see_cart') }}
            </VBtn>

            <VBtn
              color="primary"
              class="mb-4"
              variant="outlined"
              @click="generateOrder"
            >
              {{ $t('cart.generate_order') }}
            </VBtn>
          </div>
          <div class="d-flex justify-space-between align-center w-100">
            <p class="ma-0">
              {{ $t('cart.total') }}
            </p>
            <p class="ma-0" v-if="cartStore.productsCartTotal">
              {{ $filters.currencyFormat(cartStore.productsCartTotal) }}
            </p>
          </div>
        </VCardActions>
      </v-card>

    </v-menu>
  </div>
</template>

<style scoped lang="scss">
.product-item {
  gap: 5px;
}

.v-card-title {
  background: rgb(var(--v-theme-primary));
}

.v-input.v-input--horizontal.v-input--center-affix.v-input--density-default.v-input--dirty.v-text-field {
  max-width: 125px;
}

</style>

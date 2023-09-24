<script setup lang="ts">
import type { Ref } from 'vue'
import { useUserStore } from "@/store/user";


let menu: Ref<boolean> = ref(false)

const cartStore = useUserStore()
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

      <v-card min-width="300">
        <VCardTitle>
          <p class="text-white text-body-1 ma-0">{{ $t('cart.my_products') }}</p>
        </VCardTitle>
        <VCardText class="pa-4">
          <p v-if="cartStore.getProductsCart.length === 0" class="pa-1 pt-1">
            {{ $t('cart.no_products') }}
          </p>
          <v-virtual-scroll
            v-else
            :height="100"
            :items="cartStore.getProductsCart"
          >
            <template v-slot:default="{ item }">
              <div class="product-item d-flex justify-start align-center mb-4">
                <VIcon @click="cartStore.removeFromCart(item)">
                  mdi-close
                </VIcon>
                <img :src="item.product.image" width="35" height="35" class="rounded-circle" alt="product-img"/>
                <div class="d-flex justify-space-between w-75">
                  <p class="ma-0">{{ item.product.name }}</p>
                  <p class="ma-0">{{ item.quantity }}</p>
                </div>
              </div>

            </template>
          </v-virtual-scroll>
        </VCardText>
        <VCardActions>
          <div class="d-flex justify-space-between w-100">
            <p>
              _Total
            </p>
            <p v-if="cartStore.getProductsCart.length === 0">
              0.00 $
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

</style>

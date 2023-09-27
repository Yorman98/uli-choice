<script setup lang="ts">
import { defineExpose, ref } from 'vue'
import type { Ref, UnwrapRef } from 'vue'
import ProductService from '@/services/ProductService'
import type { ProductCartInterface } from '@/store/types/ProductInterface'
import { STORAGE_PATH } from '@/utils/constants'

defineProps({
  hasDelete: {
    type: Boolean,
    default: true,
  },
})

const productsCartList: Ref<ProductCartInterface[]> = ref([])
const userId: Ref<UnwrapRef<number>> = ref(0)

async function updateCart(clientId: number) {
  if (!clientId)
    return

  userId.value = clientId

  const { data } = await ProductService.getProductsCart(clientId)

  productsCartList.value = data?.products as ProductCartInterface[]
}

async function deleteProductCart(productId: number) {
  await ProductService.removeFromCart(productId)
  updateCart(userId.value)
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
          <ul
            v-if="productsCartList.length > 0"
            class="cart-item"
          >
            <li
              v-for="product in productsCartList"
              :key="product.id"
              class="d-flex"
            >
              <div class="cart-images">
                <VImg
                  :src="`${STORAGE_PATH}/${product.image}`"
                  max-width="150"
                  height="150"
                  contain
                />
              </div>

              <div class="cart-details d-flex row justify-space-between">
                <div>
                  <h4>{{ product.name }}</h4>
                  <p class="ma-0">
                    {{ $t('navbar.quantity') }}: {{ product.quantity }}
                  </p>
                  <div>
                    <p class="ma-0">
                      {{ $t('navbar.attributes') }}:
                    </p>
                    <ul>
                      <li v-for="attr in product.attributes" :key="attr">
                        {{ attr }}
                      </li>
                    </ul>
                  </div>
                </div>

                <div>
                  <h4>{{ $t('cart.price_unit') }}</h4>
                  <p class="ma-0">
                    {{ product.unit_price }}
                  </p>
                </div>

                <div>
                  <h4>{{ $t('cart.total') }}</h4>
                  <p class="ma-0">
                    {{ product.unit_price * product.quantity }}
                  </p>
                </div>

                <div
                  v-if="hasDelete"
                  class="cart-delete d-flex align-center"
                >
                  <VIcon
                    size="30"
                    @click="deleteProductCart(product.id)"
                  >
                    mdi-delete-outline
                  </VIcon>
                </div>
              </div>
            </li>
          </ul>

          <div
            v-else
            class="no-cart-item"
          >
            <p>{{ $t('cart.no_products') }}</p>
          </div>
        </VCol>
      </VRow>
    </VCardTitle>
  </VCard>
</template>

<style lang="scss" scoped>
.admin-cart-container {
  ul.cart-item {
    list-style: none;

    > li {
      margin-bottom: 20px;
    }
  }

  .no-cart-item p {
    font-size: 16px;
    text-align: center;
    font-style: italic;
  }

  .cart-images {
    width: 20%;
  }

  .cart-details {
    width: 80%;
    padding: 0 20px;

    h4 {
      font-size: 16px;
    }

    p {
      font-size: 14px;
    }

    ul {
      font-size: 14px;
      padding-left: 40px;
    }
  }

  .cart-delete {
    color: red;
  }
}
</style>

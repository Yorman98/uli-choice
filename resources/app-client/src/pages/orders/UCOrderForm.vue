<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { ProductCartRequestInterface, ProductInterface } from '@/store/types/ProductInterface'
import type { ClientInterface } from '@/store/types/ClientInterface'
import type { VariationInterface, VariationSelectInterface } from '@/store/types/VariationInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import ProductService from '@/services/ProductService'
import ClientService from '@/services/ClientService'
import OrderService from '@/services/OrderService'
import UCAdminCart from '@/components/adminCart/UCAdminCart.vue'
import type { OrderInterface } from '@/store/types/OrderInterface'
import router from '@/router'

const { t } = useI18n()

const adminCart = ref(null)
const processOrder = ref(false)

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('orders.orders_list'),
    disabled: false,
    to: {
      name: 'ordersList', // TODO: Change to orders list
    },
  },
  {
    title: t('orders.add_order'),
    disabled: true,
  },
])

const clientList: Ref<ClientInterface[]> = ref([])
const productsList: Ref<ProductInterface[]> = ref([])
const variationsList: Ref<VariationSelectInterface[]> = ref([])
const cartProducts: Ref<OrderInterface> = ref({} as OrderInterface)

const cartInfo: UnwrapNestedRefs<ProductCartRequestInterface> = reactive({
  user_id: null,
  product_id: null,
  variation_id: null,
  quantity: null,
} as ProductCartRequestInterface)

async function dataProducts() {
  const response = await ProductService.getProducts()
  const data = response?.data?.data ?? []

  data.data.forEach((product: ProductInterface) => {
    productsList.value.push(product)
  })
}

async function dataClients() {
  const { data } = await ClientService.getClients()
  const response = data?.data ?? []

  response.forEach((client: ClientInterface) => {
    clientList.value.push(client)
  })
}

onMounted(async () => {
  await dataClients()
  await dataProducts()
})

async function variationProduct() {
  cartInfo.variation_id = null
  variationsList.value = []

  const { data } = await ProductService.getVariationByProduct(cartInfo.product_id)
  const response = data.variations ?? []

  response.forEach((variation: VariationInterface) => {
    const attributes = variation.attributes.map(attr => `${attr.name}`).join(' - ')

    variationsList.value.push({ id: variation.id, attributes })
  })
}

async function saveProductsCart() {
  await ProductService.addProductCart(cartInfo)
  adminCart.value.updateCart(cartInfo.user_id)

  await updateCartByClient()
  Object.assign(cartInfo, {
    product_id: null,
    variation_id: null,
    quantity: null,
  })
}

async function updateCartByClient() {
  processOrder.value = false

  const { data } = await ProductService.getProductsActiveCart(cartInfo.user_id)

  cartProducts.value.cart_id = (data?.cart_id as number)
  adminCart.value.updateCart(cartInfo.user_id)
  if (cartProducts.value.cart_id)
    processOrder.value = true
}

async function saveOrder() {
  const { data } = await OrderService.createOrder({
    cart_id: cartProducts.value.cart_id,
  })

  await updateCartByClient()
  Object.assign(cartInfo, {
    user_id: null,
    product_id: null,
    variation_id: null,
    quantity: null,
  })
  cartProducts.value = {} as OrderInterface
  processOrder.value = false

  router.push({ name: 'editOrderForm', params: { id: data.order_id } })
}
</script>

<template>
  <VRow class="uc-order-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('orders.add_order')"
        :path="path"
      />

      <div class="d-flex flex-wrap align-start order-details">
        <VCard
          class="mb-6 add-order"
          :title="$t('orders.order_details')"
        >
          <VCardTitle class="d-flex justify-end mb-4">
            <VRow>
              <VCol
                cols="12"
                class="d-flex justify-end flex-column"
              >
                <VSelect
                  v-model="cartInfo.user_id"
                  :items="clientList"
                  item-title="first_name"
                  item-value="id"
                  :label="$t('navbar.clients')"
                  class="mb-6"
                  density="compact"
                  @update:model-value="updateCartByClient"
                />

                <VSelect
                  v-model="cartInfo.product_id"
                  :items="productsList"
                  item-title="name"
                  item-value="id"
                  :label="$t('navbar.products')"
                  class="mb-6"
                  density="compact"
                  @update:model-value="variationProduct"
                />

                <VSelect
                  v-model="cartInfo.variation_id"
                  :items="variationsList"
                  item-title="attributes"
                  item-value="id"
                  :label="$t('navbar.variation')"
                  class="mb-6"
                  density="compact"
                />

                <VTextField
                  v-model="cartInfo.quantity"
                  :label="$t('navbar.quantity')"
                  type="number"
                  class="mb-6"
                  density="compact"
                  @update:model-value="cartInfo.quantity = parseInt(cartInfo.quantity as string)"
                />

                <div class="text-end">
                  <VBtn
                    :disabled="!cartInfo.user_id || !cartInfo.product_id || !cartInfo.variation_id || cartInfo.quantity < 1"
                    @click="saveProductsCart"
                  >
                    <VIcon size="30">
                      mdi-content-save-outline
                    </VIcon>
                    <p class="text-button ma-0 text-white">
                      {{ t('orders.add_to_order') }}
                    </p>
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VCardTitle>
        </VCard>

        <div class="mb-6 cart-order">
          <UCAdminCart ref="adminCart" />
        </div>
      </div>

      <div class="save-order">
        <VBtn
          :disabled="!processOrder"
          @click="saveOrder"
        >
          <VIcon size="25">
            mdi-cart-check
          </VIcon>
          <p class="text-button text-white ma-0">
            {{ t('orders.process_order') }}
          </p>
        </VBtn>
      </div>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.uc-order-container {
  h4 {
    font-size: 30px !important;
    line-height: 1;
  }

  .order-details {
    gap: 30px;
  }

  .save-order {
    padding: 12px;
    width: 100%;
    text-align: end;

    p {
      padding-left: 5px;
    }
  }

  .add-order {
    width: calc(35% - 15px);
  }

  .cart-order {
    width: calc(65% - 15px);
  }

  @media screen and (max-width: 1024px) {
    .add-order, .cart-order {
      width: 100%;
    }
  }
}
</style>

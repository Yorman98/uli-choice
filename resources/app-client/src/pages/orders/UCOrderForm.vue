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

const { t } = useI18n()

const adminCart = ref(null)

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('sales.order_list'),
    disabled: false,
    to: {
      name: 'adminDashboard', // TODO: Change to orders list
    },
  },
  {
    title: t('sales.add_order'),
    disabled: true,
  },
])

const clientList: Ref<ClientInterface[]> = ref([])
const productsList: Ref<ProductInterface[]> = ref([])
const variationsList: Ref<VariationSelectInterface[]> = ref([])
const cardInfo: Ref<OrderInterface> = ref({} as OrderInterface)

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

  Object.assign(cartInfo, {
    user_id: null,
    product_id: null,
    variation_id: null,
    quantity: null,
  })
}

async function updateCartByClient() {
  const { data } = await productService.getProductsCart(cartInfo.user_id)

  cardInfo.value.cart_id = data?.cart_id
  adminCart.value.updateCart(cartInfo.user_id)
}

async function saveOrder() {
  await OrderService.createOrder({
    cart_id: cardInfo.value.cart_id,
  })

  updateCartByClient()
  Object.assign(cartInfo, {
    user_id: null,
    product_id: null,
    variation_id: null,
    quantity: null,
  })
  cardInfo.value = {} as OrderInterface
}
</script>

<template>
  <VRow class="uc-order-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.transactions')"
        :path="path"
      />

      <div class="d-flex order-details">
        <VCard
          class="mb-6 w-50"
          :title="$t('sales.order_details')"
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
                      {{ t('sales.add_order') }}
                    </p>
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VCardTitle>
        </VCard>

        <VCard
          class="mb-6 w-50"
          :title="$t('sales.order_details')"
        >
          <VCardTitle class="d-flex justify-end mb-4">
            <VRow>
              <VCol
                cols="12"
                class="d-flex justify-end flex-column"
              >
                transactions... in progress
              </VCol>
            </VRow>
          </VCardTitle>
        </VCard>
      </div>

      <UCAdminCart ref="adminCart" />
    </VCol>

    <div class="save-order">
      <VBtn @click="saveOrder">
        <VIcon size="35">
          mdi-content-save-outline
        </VIcon>
        <p class="text-button text-white ma-0">
          {{ t('global.save') }}
        </p>
      </VBtn>
    </div>
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
  }
}
</style>

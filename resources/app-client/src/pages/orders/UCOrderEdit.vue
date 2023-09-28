<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import type { OrderInterface } from '@/store/types/OrderInterface'
import type { ClientInterface } from '@/store/types/ClientInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import ClientService from '@/services/ClientService'
import OrderService from '@/services/OrderService'
import UCAdminCart from '@/components/adminCart/UCAdminCart.vue'
import UCTransactions from '@/components/global/UCTransactions.vue'

const { t } = useI18n()

const route = useRoute()

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
    title: t('orders.orders_list'),
    disabled: false,
    to: {
      name: 'adminDashboard', // TODO: Change to orders list
    },
  },
  {
    title: t('orders.update_order'),
    disabled: true,
  },
])

const status: Ref<any[]> = ref([
  {
    name: t('global.status.pending'),
    value: 1,
  },
  {
    name: t('global.status.processing'),
    value: 2,
  },
  {
    name: t('global.status.completed'),
    value: 3,
  },
  {
    name: t('global.status.cancelled'),
    value: 4,
  },
])

const orderDetails: Ref<OrderInterface> = ref({} as OrderInterface)
const userDetails: Ref<ClientInterface> = ref({} as ClientInterface)
const cantProducts: Ref<number> = ref(0)

const orderUpdateInfo: UnwrapNestedRefs<OrderInterface> = reactive({
  status_id: null,
} as OrderInterface)

async function dataOrderDetails() {
  const { data } = await OrderService.getOrder(Number(route.params.id))

  orderDetails.value = data?.data as OrderInterface

  await dataUserDetails(orderDetails.value.cart.user_id)

  cantProducts.value = orderDetails.value.cart.products.length

  Object.assign(orderUpdateInfo, {
    id: orderDetails.value.id,
    status_id: orderDetails.value.status_id,
  })

  adminCart.value.updateCart(null, orderDetails.value.cart_id)
}

async function dataUserDetails(userId: number) {
  const { data } = await ClientService.getClientById(userId)

  userDetails.value = data?.data as ClientInterface
}

onMounted(async () => {
  await dataOrderDetails()
})

async function uploadOrder() {
  await OrderService.updateOrder(orderUpdateInfo)

  Object.assign(orderUpdateInfo, {
    status_id: null,
  })
  await dataOrderDetails()
}
</script>

<template>
  <VRow class="uc-edit-order-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('orders.order_details')"
        :path="path"
      />

      <div class="d-flex flex-wrap align-start justify-space-between">
        <VCard class="mb-6 order-details">
          <VCardTitle class="d-flex justify-end pt-6 pb-6">
            <VRow>
              <VCol
                cols="12"
                class="d-flex justify-end flex-column"
              >
                <h4 class="mb-6">
                  {{ $t('global.headers.order') }} # {{ orderDetails.reference }}
                </h4>
                <div class="mb-6 client-info">
                  <p class="ma-0 details-title">
                    {{ $t('global.information_client') }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('global.headers.name') }}:</span> {{ userDetails.first_name }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('global.headers.email') }}:</span> <a href="mailto:{{userDetails.email}}">{{ userDetails.email }}</a>
                  </p>
                </div>

                <div class="mb-6">
                  <p class="ma-0 details-title">
                    {{ $t('orders.order_details') }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('cart.products_cant') }}:</span> {{ cantProducts }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('cart.total_price') }}:</span> {{ orderDetails.total_price }}$
                  </p>
                </div>

                <div>
                  <p class="ma-0 details-title">
                    {{ $t('orders.order_status') }}
                  </p>
                  <div class="d-flex flex-row align-start mt-2 update-status">
                    <VSelect
                      v-model="orderUpdateInfo.status_id"
                      :items="status"
                      item-title="name"
                      item-value="value"
                      :label="$t('global.headers.status')"
                      class="mb-6"
                      density="compact"
                    />

                    <VBtn @click="uploadOrder">
                      <VIcon size="25">
                        mdi-content-save-outline
                      </VIcon>
                      <p class="text-button text-white ma-0">
                        {{ t('global.update') }}
                      </p>
                    </VBtn>
                  </div>
                </div>
              </VCol>
            </VRow>
          </VCardTitle>
        </VCard>

        <div class="order-transactions">
          <VCard class="mb-6 ">
            <VCardTitle class="d-flex justify-end pt-6 pb-6">
              <VRow>
                <VCol
                  v-if="orderDetails.id && orderDetails.id > 0"
                  cols="12"
                  class="d-flex justify-end flex-column"
                >
                  <UCTransactions :order="orderDetails.id" />
                </VCol>
              </VRow>
            </VCardTitle>
          </VCard>

          <UCAdminCart
            ref="adminCart"
            :has-delete="false"
          />
        </div>
      </div>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.uc-edit-order-container {
  h4 {
    font-size: 20px !important;
    line-height: 1;
  }

  .order-details {
    width: calc(30% - 15px);

    .details-title {
      font-weight: 700;
      font-size: 18px;
    }

    p {
      font-size: 16px;
      font-weight: 300;

      span {
        font-weight: 500;
      }
    }
  }

  .order-transactions {
    width: calc(70% - 15px);
  }

  .update-status {
    gap: 10px;
  }

  @media screen and (min-width: 1024px) and (max-width: 1600px) {
    .order-details {
      ::v-deep(.v-row) {
        word-wrap: break-word;
        display: contents;

        .client-info p, .update-status {
          display: flex !important;
          flex-direction: column !important;

          div {
            margin-bottom: 0 !important;
          }
        }
      }
    }
  }

  @media screen and (max-width: 1024px) {
    .order-details, .order-transactions {
      width: 100%;
    }
  }
}
</style>

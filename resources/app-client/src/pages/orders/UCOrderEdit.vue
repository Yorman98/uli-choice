<script setup lang="ts">
import type { Ref } from 'vue'
import { ref } from 'vue'
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
    title: t('sales.order_list'),
    disabled: false,
    to: {
      name: 'adminDashboard', // TODO: Change to orders list
    },
  },
  {
    title: t('sales.update_order'),
    disabled: true,
  },
])

const orderDetails: Ref<OrderInterface> = ref({} as OrderInterface)
const userDetails: Ref<ClientInterface> = ref({} as ClientInterface)
const cantProducts: Ref<number> = ref(0)

async function dataOrderDetails() {
  const { data } = await OrderService.getOrder(Number(route.params.id))

  orderDetails.value = data?.data as OrderInterface

  await dataUserDetails(orderDetails.value.cart.user_id)

  cantProducts.value = orderDetails.value.cart.products.length
}

async function dataUserDetails(userId: number) {
  const { data } = await ClientService.getClientById(userId)

  userDetails.value = data?.data as ClientInterface
}

onMounted(async () => {
  await dataOrderDetails()
})
</script>

<template>
  <VRow class="uc-edit-order-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.transactions')"
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
                <div class="mb-6">
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
                    {{ $t('sales.order_details') }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('cart.products_cant') }}:</span> {{ cantProducts }}
                  </p>
                  <p class="ma-0">
                    <span>{{ $t('cart.total_price') }}:</span> {{ orderDetails.total_price }}$
                  </p>
                </div>

                <div>
                  <p>Status: XXX</p>
                </div>
              </VCol>
            </VRow>
          </VCardTitle>
        </VCard>

        <VCard class="mb-6 order-transactions">
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
      </div>

      <UCAdminCart
        ref="adminCart"
        :has-delete="false"
      />
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
    width: calc(40% - 15px);

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
    width: calc(60% - 15px);
  }

  @media screen and (max-width: 1024px) {
    .order-details, .order-transactions {
      width: 100%;
    }
  }
}
</style>

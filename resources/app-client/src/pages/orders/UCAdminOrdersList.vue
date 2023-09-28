<script setup lang="ts">
import UCTable from "@/components/helpers/UCTable.vue";
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'

import { useUserStore } from '@/store/user'
import { useI18n } from 'vue-i18n'
import { OrderInterface } from '@/store/types/OrderInterface'
import { useRouter } from 'vue-router'
import OrderService from '@/services/OrderService'

const { t } = useI18n()

const ordersStore = useUserStore()

const router = useRouter()

const headers: any[] = [
  { title: t('global.headers.name'), align: 'start', sortable: false, key: 'cart.user.first_name' },
  { title: t('global.headers.status'), key: 'status.name' },
  { title: t('cart.total'), key: 'total_price' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('orders.orders_title'),
    disabled: true,
  },
]

let orders: Ref<OrderInterface[]> = ref([])

onMounted(async () => {
  await initData()
})

async function initData () {
  const response = await ordersStore.fetchOrders()
  orders.value = response.data.data
}

async function deleteItem (orderId) {
  await OrderService.deleteOrder(orderId)
}

async function editItem (payload: any) {
  await router.push({
    name: 'editOrderForm',
    params: {
      id: payload?.id
    }
  })
}

async function getInvoice(order) {
  const response = await OrderService.getInvoice(order.id);
  const linkSource = `data:application/pdf;base64,${response.data}`;
  const downloadLink = document.createElement("a");
  const fileName = `Invoice-${order.reference}.pdf`;
  downloadLink.href = linkSource;
  downloadLink.download = fileName;
  downloadLink.click()
};

</script>

<template>
<VRow>
  <VCol cols=12>
    <UCHeaderPage
      class="mb-5"
      :title="$t('orders.orders_list')"
      :path="path"
    />

    <VCard class="pa-4">
      <VCardTitle class="d-flex justify-end mb-4">
        <VBtn @click="router.push({ name: 'addOrderForm' })">
          <VIcon
            color="white pr-2"
            size="35"
          >
            mdi-plus
          </VIcon>
          <p class="text-white text-button ma-0">
            {{ t('orders.add_order') }}
          </p>
        </VBtn>
      </VCardTitle>

      <VCardText>
        <UCTable
          :headers="headers"
          :onlyEdit="true"
          :items="orders"
          @editItem="editItem"
          @getInvoice="getInvoice"
          @deleteItem="deleteItem"
        />
      </VCardText>
    </VCard>

  </VCol>
</VRow>
</template>

<style scoped lang="scss">

</style>

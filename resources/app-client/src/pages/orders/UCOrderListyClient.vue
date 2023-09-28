<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref } from 'vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import OrderService from '@/services/OrderService'
import type { OrderInterface } from '@/store/types/OrderInterface'

const { t } = useI18n()
const router = useRouter()

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('navbar.orders'),
    disabled: true,
  },
])

const headers: any[] = [
  { title: t('global.headers.id'), key: 'id' },
  { title: t('global.headers.date_created'), key: 'created_at' },
  { title: t('global.headers.status'), key: 'statusName' },
  { title: t('global.headers.amount'), key: 'total_price', align: 'end' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const ordersList: Ref<OrderInterface[]> = ref([])

async function getData() {
  const { data } = await OrderService.getOrders()
  const response = data?.data?.data ?? []

  response.forEach((order: OrderInterface) => {
    order.statusName = t(`global.status.${order?.status?.name.toLowerCase()}`)
    ordersList.value.push(order)
  })

  ordersList.value.sort((a: OrderInterface, b: OrderInterface) => {
    const dateA = new Date(a.created_at)
    const dateB = new Date(b.created_at)

    return dateB - dateA
  })
}

onMounted(() => {
  getData()
})

function goToItem(order: OrderInterface) {
  router.push({
    name: 'orderDetail2',
    params: {
      id: order.id,
    },
  })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.orders')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardText>
          <UCTable
            :headers="headers"
            :items="ordersList"
            onlyGoTo
            @editItem="editItem"
            @goToItem="goToItem"
          />
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
.v-btn {
  span.v-btn__content p {
    color: #FFFFFF;
  }
}

li {
    list-style: none;
}

.v-icon {
    cursor: pointer;
}
</style>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import OrderService from '@/services/OrderService'
import TransactionService from '@/services/TransactionService'
import type { OrderInterface } from '@/store/types/OrderInterface'
import type { TransactionInterface } from '@/store/types/TransactionInterface'
import type { ProductInterface } from '@/store/types/ProductInterface'

const { t } = useI18n()
const router = useRouter()

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'products',
    },
  },
  {
    title: t('navbar.orders'),
    disabled: false,
    to: {
      name: 'orders2',
    },
  },
  {
    title: t('orders.order_details'),
    disabled: true,
  },
])

const headersProducts: any[] = [
  { title: t('global.headers.product_name'), key: 'product.name' },
  { title: t('global.headers.quantity'), key: 'quantity', align: 'center' },
  { title: t('global.headers.amount'), key: 'unit_price', align: 'end' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const headersTransactions: any[] = [
  { title: t('global.headers.payment_method'), key: 'payment_method.name' },
  { title: t('global.headers.amount'), key: 'amount' },
  { title: t('global.headers.date'), key: 'created_at' },
  { title: t('global.headers.notes'), key: 'notes' },
]

const orderInfo: UnwrapNestedRefs<OrderInterface> = reactive({
  cart_id: 0,
} as OrderInterface)

const transactionList: Ref<TransactionInterface[]> = ref([])
const remainingAmount: Ref<number> = ref(0)

async function getData() {
  const id = Number(router.currentRoute.value.params.id)
  const { data } = await OrderService.getOrder(id)

  const order = data?.data ?? []
  if (order?.cart?.user)
    order.user_full_name = `${order?.cart?.user.first_name} ${order?.cart?.user.last_name}`

  order.statusName = t(`global.status.${order?.status?.name.toLowerCase()}`)

  Object.assign(orderInfo, order)
  remainingAmount.value = orderInfo.total_price

  const data2 = await TransactionService.getTransaction(id)
  const response = data2.data?.transactions ?? []

  response.forEach((transaction: TransactionInterface) => {
    transactionList.value.push(transaction)
    remainingAmount.value = remainingAmount.value - transaction.amount
  })

  transactionList.value.sort((a: TransactionInterface, b: TransactionInterface) => {
    const dateA = new Date(a.created_at)
    const dateB = new Date(b.created_at)

    return dateB - dateA
  })
}

onMounted(() => {
  getData()
})

function goToItem(product: ProductInterface) {
  console.log(product);
  router.push({
    name: 'product',
    params: {
      id: product.product.id,
    },
  })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-4"
        :title="$t('navbar.orders')"
        :path="path"
      />

      <VCard
        class="pa-4 mb-7"
        :title="t('orders.order_details')"
      >
        <VCardText>
          <VRow>
            <VCol cols="4">
              <h3>{{ $t('orders.reference') }}</h3>
              <p>{{ orderInfo.reference }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('global.headers.date_created') }}</h3>
              <p>{{ orderInfo.created_at }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('orders.amount') }}</h3>
              <p>${{ orderInfo.total_price }}</p>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="4">
              <h3>{{ $t('orders.client') }}</h3>
              <p>{{ orderInfo.user_full_name }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('global.headers.status') }}</h3>
              <p>{{ orderInfo.statusName }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('orders.remaining_amount') }}</h3>
              <p>${{ remainingAmount }}</p>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <VCard
        class="pa-4 mb-7"
        :title="t('orders.product_list')"
      >
        <VCardText>
          <UCTable
            only-go-to
            @goToItem="goToItem"
            :headers="headersProducts"
            :items="orderInfo.cart?.products"
          />
        </VCardText>
      </VCard>

      <VCard
        class="pa-4 mb-7"
        :title="t('orders.transactions_list')"
      >
        <VCardText>
          <UCTable
            :headers="headersTransactions"
            :items="transactionList"
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

h3 {
  font-weight: 500;
}
</style>

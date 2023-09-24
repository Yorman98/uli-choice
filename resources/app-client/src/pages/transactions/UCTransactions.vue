<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { TransactionInterface } from '@/store/types/TransactionInterface'
import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'
import type { OrderInterface } from '@/store/types/OrderInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'

const { t } = useI18n()

const path: Ref<any[]> = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('transaction.store_transaction'),
    disabled: true,
  },
])

const openTransaction: Ref<boolean> = ref(false)
const isEditTransaction: Ref<boolean> = ref(false)

// TODO: Actualize headers keys
const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.method_payment'), key: 'provider.name' },
  { title: t('global.headers.order'), key: 'description' },
  { title: t('global.headers.amount'), key: 'total' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const transactionsList: Ref<TransactionInterface[]> = ref([])
const paymentMethodsList: Ref<PaymentMethodInterface[]> = ref([])
const ordersList: Ref<OrderInterface[]> = ref([])

const transactionInfo: UnwrapNestedRefs<TransactionInterface> = reactive({
  order_id: null,
  payment_method_id: null,
  amount: null,
  notes: '',
} as TransactionInterface)

async function dataTransactions() {
  // TODO: Get data from API
}

async function dataPaymentMethods() {
  // TODO: Get data from API
}

onMounted(async () => {
  await dataTransactions()
  await dataPaymentMethods()
})

function closeTransaction() {
  openTransaction.value = false
  Object.assign(transactionInfo, {
    order_id: null,
    payment_method_id: null,
    amount: null,
    notes: '',
  })
}

async function saveTransaction() {
  if (isEditTransaction.value) {
    // TODO: Add edit method
    isEditTransaction.value = false
  }
  else {
    // TODO: Add save method
  }

  transactionsList.value = []
  await dataTransactions()
  closeTransaction()
}

function editTransaction(transaction: TransactionInterface) {
  isEditTransaction.value = true
  openTransaction.value = true
  Object.assign(transactionInfo, transaction)
}

async function deleteTransaction(payload: number) {
  // TODO: Add delete method
  console.log(payload)
  transactionsList.value = []
  await dataTransactions()
}

function addTransaction() {
  if (!isEditTransaction.value) {
    Object.assign(transactionInfo, {
      order_id: null,
      payment_method_id: null,
      amount: null,
      notes: '',
    })
  }
  openTransaction.value = true
}
</script>

<template>
  <VRow class="uc-transaction-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.transactions')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="addTransaction">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0 text-white">
              {{ t('transaction.add_transaction') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="transactionsList"
            has-sub-items
            @editItem="editTransaction"
            @deleteItem="deleteTransaction"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openTransaction"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle color="red">
            <h4 class="text-h4 mb-2 white--text">
              {{ t('transaction.add_transaction') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VSelect
              v-model="transactionInfo.payment_method_id"
              :items="paymentMethodsList"
              item-title="name"
              item-value="id"
              :label="$t('global.headers.method_payment')"
              class="mb-6"
              density="compact"
            />

            <VSelect
              v-model="transactionInfo.order_id"
              :items="ordersList"
              item-title="reference"
              item-value="id"
              :label="$t('global.headers.order')"
              class="mb-6"
              density="compact"
            />

            <VTextField
              v-model="transactionInfo.amount"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.amount')"
            />

            <VTextField
              v-model="transactionInfo.notes"
              density="compact"
              :label="$t('global.headers.description')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closeTransaction"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="saveTransaction"
            >
              {{ isEditTransaction ? $t('global.update') : $t('global.save') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.uc-transaction-container {
  h4 {
    font-size: 30px !important;
    line-height: 1;
  }
}
</style>

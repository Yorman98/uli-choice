<script setup lang="ts">
import { VDataTable } from 'vuetify/labs/VDataTable'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { TransactionInterface } from '@/store/types/TransactionInterface'
import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'
import PaymentMethodService from '@/services/PaymentMethodService'
import TransactionService from '@/services/TransactionService'
import { formatDate } from '@/utils/dateUtils'

const props = defineProps({
  order: Number,
})

const { t } = useI18n()

const openTransaction: Ref<boolean> = ref(false)
const isEditTransaction: Ref<boolean> = ref(false)
const totalPaid: Ref<number> = ref(0)
const totalPending: Ref<number> = ref(0)
const orderId: Ref<number> = ref(0)

const headers: any[] = [
  { title: t('global.headers.method_payment'), align: 'start', key: 'payment_method.name' },
  { title: t('global.headers.amount'), key: 'amount' },
  { title: t('global.headers.date'), key: 'created_at' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const transactionsList: Ref<TransactionInterface[]> = ref([])
const paymentMethodsList: Ref<PaymentMethodInterface[]> = ref([])

const transactionInfo: UnwrapNestedRefs<TransactionInterface> = reactive({
  order_id: null,
  payment_method_id: null,
  amount: null,
  notes: '',
} as TransactionInterface)

async function dataTransactions() {
  transactionInfo.order_id = orderId.value

  const { data } = await TransactionService.getTransaction(orderId.value)
  const response = data?.transactions ?? []

  response.forEach((transaction: TransactionInterface) => {
    if (transaction.created_at != null) {
      transactionsList.value.push({
        ...transaction,
        created_at: formatDate(transaction.created_at),
      })
    }
  })

  totalPaid.value = data?.total_paid ?? 0
  totalPending.value = data?.total_pending ?? 0
}

async function dataPaymentMethods() {
  const { data } = await PaymentMethodService.getPaymentMethods()
  const response = data?.data ?? []

  response.forEach((method: PaymentMethodInterface) => {
    paymentMethodsList.value.push(method)
  })
}

onMounted(async () => {
  orderId.value = props.order as number

  await dataTransactions()
  await dataPaymentMethods()
})

function closeTransaction() {
  openTransaction.value = false
  isEditTransaction.value = false
  Object.assign(transactionInfo, {
    id: null,
    amount: null,
    notes: '',
  })
}

async function saveTransaction() {
  if (isEditTransaction.value) {
    await TransactionService.updateTransaction(transactionInfo)
    isEditTransaction.value = false
  }
  else {
    await TransactionService.createTransaction(transactionInfo)
  }

  transactionsList.value = []
  await dataTransactions()
  closeTransaction()
}

function editTransaction(transaction: TransactionInterface) {
  isEditTransaction.value = true
  openTransaction.value = true
  Object.assign(transactionInfo, transaction.selectable)
}

async function deleteTransaction(payload: number) {
  await TransactionService.deleteTransaction(payload.selectable.id)
  transactionsList.value = []
  await dataTransactions()
}

function addTransaction() {
  if (!isEditTransaction.value) {
    Object.assign(transactionInfo, {
      id: null,
      amount: null,
      notes: '',
    })
  }
  openTransaction.value = true
}
</script>

<template>
  <div class="uc-transaction-container">
    <div class="d-flex align-start justify-space-between mb-6">
      <h4>{{ $t('navbar.transactions') }} </h4>
      <VBtn
        density="comfortable"
        @click="addTransaction"
      >
        <VIcon
          color="white pr-2"
          size="25"
        >
          mdi-plus
        </VIcon>
        <p class="text-button ma-0 text-white">
          {{ t('transaction.add_transaction') }}
        </p>
      </VBtn>
    </div>

    <div>
      <VDataTable
        :headers="headers as any"
        :items="transactionsList"
        class="uc-table elevation-1"
      >
        <template #item.actions="{ item }">
          <VMenu location="left">
            <template #activator="{ props }">
              <VIcon v-bind="props">
                mdi-dots-vertical
              </VIcon>
            </template>

            <VList>
              <VListItem @click="editTransaction(item)">
                <template #prepend>
                  <VIcon icon="mdi-pencil-outline" />
                </template>

                <VListItemTitle v-text="$t('global.edit')" />
              </VListItem>

              <VListItem @click="deleteTransaction(item)">
                <template #prepend>
                  <VIcon icon="mdi-delete-outline" />
                </template>

                <VListItemTitle v-text="$t('global.delete')" />
              </VListItem>
            </VList>
          </VMenu>
        </template>
        <template #no-data>
          <p class="mt-2 mb-2">
            {{ $t('transaction.transactions_not_available') }}
          </p>
        </template>
      </VDataTable>

      <div class="text-end mt-5 transaction-totals">
        <p class="ma-0">
          {{ $t('transaction.total_paid') }}: <span>{{ totalPaid }}$</span>
        </p>
        <p class="ma-0">
          {{ $t('transaction.total_pending') }}: <span>{{ totalPending }}$</span>
        </p>
      </div>
    </div>

    <VDialog
      v-model="openTransaction"
      persistent
      max-width="700px"
    >
      <VCard class="pa-4">
        <VCardTitle color="red">
          <h4 class="text-h4 mb-2 white--text">
            {{ isEditTransaction ? $t('transaction.edit_transaction') : $t('transaction.add_transaction') }}
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
  </div>
</template>

<style lang="scss" scoped>
.uc-transaction-container {
  .uc-table {
    ::v-deep thead th, ::v-deep tbody td {
      height: 35px !important;
      font-size: 17px;
      text-transform: capitalize !important;
    }

    ::v-deep .v-data-table-footer {
      display: none !important;
    }
  }

  .transaction-totals {
    p {
      font-size: 18px;
      font-weight: 500;

      span {
        font-weight: 700;
      }
    }
  }
}

h4 {
  font-size: 22px !important;
  font-weight: 500;
  line-height: 1;
}
</style>

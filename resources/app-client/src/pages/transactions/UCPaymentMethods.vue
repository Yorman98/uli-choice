<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import type { PaymentMethodInterface } from '@/store/types/PaymentMethodInterface'

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
    title: t('navbar.methods_payment'),
    disabled: true,
  },
])

const openPaymentMethod: Ref<boolean> = ref(false)

// TODO: Actualize headers keys
const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'provider.name' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const paymentMethodsList: Ref<PaymentMethodInterface[]> = ref([])

const paymentMethodsInfo: UnwrapNestedRefs<PaymentMethodInterface> = reactive({
  name: '',
} as PaymentMethodInterface)

function dataPaymentMethods() {
  // TODO: Get data from API
}

onMounted(async () => {
  await dataPaymentMethods()
})

function closePaymentMethod() {
  openPaymentMethod.value = false
  Object.assign(paymentMethodsInfo, {
    name: '',
  })
}

async function savePaymentMethod() {
  // TODO: Save data from API

  paymentMethodsList.value = []
  await dataPaymentMethods()
  closePaymentMethod()
}

async function deletePaymentMethod(payload: number) {
  // TODO: Delete data from API
  console.log(payload)

  paymentMethodsList.value = []
  await dataPaymentMethods()
}
function addPaymentMethod() {
  openPaymentMethod.value = true
}
</script>

<template>
  <VRow class="uc-payment-methods-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.methods_payment')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="addPaymentMethod">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0 text-white">
              {{ t('transaction.add_payment_method') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="paymentMethodsList"
            has-sub-items
            @deleteItem="deletePaymentMethod"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openPaymentMethod"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle color="red">
            <h4 class="text-h4 mb-2 white--text">
              {{ $t('transaction.add_payment_method') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VTextField
              v-model="paymentMethodsInfo.name"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.method_payment')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closePaymentMethod"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="savePaymentMethod"
            >
              {{ $t('global.save') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.uc-payment-methods-container {
  h4 {
    font-size: 30px !important;
    line-height: 1;
  }
}
</style>

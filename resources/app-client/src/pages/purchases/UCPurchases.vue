<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { PurchaseInterface } from '@/store/types/PurchaseInterface'
import type { ProviderInterface } from '@/store/types/ProviderInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import PurchaseService from '@/services/PurchaseService'
import ProviderService from '@/services/ProviderService'

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
    title: t('purchases.store_purchases'),
    disabled: true,
  },
])

const openPurchase: Ref<boolean> = ref(false)
const isViewPurchase: Ref<boolean> = ref(false)
const isEditPurchase: Ref<boolean> = ref(false)

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.provider'), key: 'provider.name' },
  { title: t('global.headers.description'), key: 'description' },
  { title: t('global.headers.total'), key: 'total' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const purchasesList: Ref<PurchaseInterface[]> = ref([])
const providersList: Ref<ProviderInterface[]> = ref([])
const dataPurchase: Ref<PurchaseInterface> = ref({} as PurchaseInterface)

const purchaseInfo: UnwrapNestedRefs<PurchaseInterface> = reactive({
  total: null,
  provider_id: null,
  description: '',
} as PurchaseInterface)

async function dataPurchases() {
  const { data } = await PurchaseService.getPurchases()
  const response = data?.data?.data ?? []

  response.forEach((purchase: PurchaseInterface) => {
    purchasesList.value.push(purchase)
  })
}

async function dataProviders() {
  const { data } = await ProviderService.getProviders()
  const response = data?.providers?.data ?? []

  response.forEach((provider: ProviderInterface) => {
    providersList.value.push(provider)
  })
}

onMounted(async () => {
  await dataPurchases()
  await dataProviders()
})

function closePurchase() {
  openPurchase.value = false
  Object.assign(purchaseInfo, {
    total: null,
    id: null,
    description: '',
  })
}

async function savePurchase() {
  if (isEditPurchase.value)
    await PurchaseService.updatePurchase(purchaseInfo)
  else
    await PurchaseService.createPurchase(purchaseInfo)

  purchasesList.value = []
  await dataPurchases()
  closePurchase()
}

function viewPurchase(purchase: PurchaseInterface) {
  isViewPurchase.value = true
  dataPurchase.value = Object.assign(purchaseInfo, purchase)

  console.log(dataPurchase)
}
function editPurchase(purchase: PurchaseInterface) {
  isEditPurchase.value = true
  openPurchase.value = true
  Object.assign(purchaseInfo, purchase)
}

async function deletePurchase(payload: number) {
  await PurchaseService.deletePurchase(payload)
  purchasesList.value = []
  await dataPurchases()
}
</script>

<template>
  <VRow class="uc-purchases-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.purchases')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="openPurchase = true">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0 text-white">
              {{ t('purchases.add_purchase') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="purchasesList"
            hasSubItems
            @goToItem="viewPurchase"
            @editItem="editPurchase"
            @deleteItem="deletePurchase"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openPurchase"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle color="red">
            <h4 class="text-h4 mb-2 white--text">
              {{ $t('purchases.add_purchase') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VSelect
              v-model="purchaseInfo.provider_id"
              :items="providersList"
              item-title="name"
              item-value="id"
              :label="$t('global.headers.provider')"
              class="mb-6"
              density="compact"
            />

            <VTextField
              v-model="purchaseInfo.total"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.total')"
            />

            <VTextField
              v-model="purchaseInfo.description"
              density="compact"
              :label="$t('global.headers.description')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closePurchase"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="savePurchase"
            >
              {{ isEditPurchase ? $t('global.update') : $t('global.save') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>

      <VDialog
        v-model="isViewPurchase"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardText>
            <h4 class="text-h4 mb-5 font-weight-bold">
              {{ $t('purchases.purchase_data') }}
            </h4>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.date_created') }}: </span>{{ dataPurchase.created_at }}
            </p>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.total') }}: </span>{{ dataPurchase.total }}
            </p>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.description') }}: </span>{{ dataPurchase.description }}
            </p>
            <h6 class="text-h6 mt-5 mb-2 font-weight-bold">
              {{ $t('purchases.provider_data') }}
            </h6>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.name') }}: </span>{{ dataPurchase.provider.name }}
            </p>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.phone') }}: </span>{{ dataPurchase.provider.phone_number }}
            </p>
            <p class="mb-1">
              <span class="font-weight-bold">{{ $t('global.headers.website') }}: </span>{{ dataPurchase.provider.website }}
            </p>
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="isViewPurchase = false"
            >
              {{ $t('global.close') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.uc-purchases-container {
  h4 {
    font-size: 30px !important;
    line-height: 1;
  }
}
</style>

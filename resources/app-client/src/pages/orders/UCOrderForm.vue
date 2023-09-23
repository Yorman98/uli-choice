<script setup lang="ts">
import type { Ref, UnwrapNestedRefs } from 'vue'
import { reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute } from 'vue-router'
import type { ProductInterface } from '@/store/types/ProductInterface'
import type { ClientInterface } from '@/store/types/ClientInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import ProductService from '@/services/ProductService'
import ClientService from '@/services/ClientService'

const { t } = useI18n()
const route = useRoute()

const isEdit = !!route.params.id

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
    title: isEdit ? t('sales.update_order') : t('sales.add_order'),
    disabled: true,
  },
])

const clientList: Ref<ClientInterface[]> = ref([])
const productsList: Ref<ProductInterface[]> = ref([])

const orderInfo: UnwrapNestedRefs<any> = reactive({
  client_id: null,
})

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
</script>

<template>
  <VRow class="uc-transaction-container">
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.transactions')"
        :path="path"
      />

      <VCard
        class="mb-6"
        :title="$t('sales.order_details')"
      >
        <VCardTitle class="d-flex justify-end mb-4">
          <VRow>
            <VCol
              cols="12"
              class="d-flex justify-end flex-column"
            >
              <VSelect
                v-model="orderInfo.client_id"
                :items="clientList"
                item-title="first_name"
                item-value="id"
                :label="$t('navbar.clients')"
                class="mb-6"
                density="compact"
              />

              <VSelect
                :items="productsList"
                item-title="name"
                item-value="id"
                :label="$t('navbar.products')"
                class="mb-6"
                density="compact"
              />
            </VCol>
          </VRow>
        </VCardTitle>
      </VCard>
    </VCol>
  </VRow>
</template>

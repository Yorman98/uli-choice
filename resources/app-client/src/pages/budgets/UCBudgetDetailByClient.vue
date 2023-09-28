<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import BudgetService from '@/services/BudgetService'
import type { BudgetInterface } from '@/store/types/BudgetInterface'
import type { ProductLinkInterface } from '@/store/types/ProductLinkInterface'

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
    title: t('navbar.budgets'),
    disabled: false,
    to: {
      name: 'budgets2',
    },
  },
  {
    title: t('budgets.details'),
    disabled: true,
  },
])

const headers: any[] = [
  { title: t('global.headers.product_url'), key: 'url' },
  { title: t('budgets.cost'), key: 'cost', align: 'end' },
  { title: t('budgets.price'), key: 'price', align: 'end' },
  { title: t('global.headers.quantity'), key: 'quantity', align: 'center' },
  { title: t('budgets.amount'), key: 'amount', align: 'end' },
]

const budgetsList: Ref<BudgetInterface[]> = ref([])

const budgetInfo: UnwrapNestedRefs<BudgetInterface> = reactive({
  cost: 0,
  price: 0,
  product_links: [],
  status_id: 1,
  user_id: 0,
} as BudgetInterface)

const existMessage: Ref<boolean> = ref(false)

async function getData() {
  const id = Number(router.currentRoute.value.params.id)
  const data = await BudgetService.getBudgets()
  const response = data.data?.data ?? []

  response.forEach((budget: BudgetInterface) => {
    if (budget?.user && budget?.user?.length > 0) {
      budget.user_full_name = `${budget?.user[0].first_name} ${budget?.user[0].last_name}`
      budget.user_id = budget?.user[0].id
    }
    budget.statusName = t(`global.status.${budget?.status?.name.toLowerCase()}`)
    budgetsList.value.push(budget)
  })

  const budgetById = budgetsList.value.find((budget: BudgetInterface) => budget.id === id)

  Object.assign(budgetInfo, budgetById)
  budgetInfo.product_links.forEach((product_link: ProductLinkInterface) => {
    product_link.amount = product_link.price * product_link.quantity
  })

  if (budgetInfo.message)
    existMessage.value = true
}

onMounted(() => {
  getData()
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-4"
        :title="$t('navbar.budgets')"
        :path="path"
      />

      <VCard
        class="pa-4 mb-7"
        :title="t('budgets.details')"
      >
        <VCardText>
          <VRow>
            <VCol cols="4">
              <h3>{{ $t('budgets.id') }}</h3>
              <p>{{ budgetInfo.id }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('global.headers.date_created') }}</h3>
              <p>{{ budgetInfo.created_at }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('budgets.amount') }}</h3>
              <p>${{ budgetInfo.price }}</p>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="4">
              <h3>{{ $t('global.headers.client') }}</h3>
              <p>{{ budgetInfo.user_full_name }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('global.headers.status') }}</h3>
              <p>{{ budgetInfo.statusName }}</p>
            </VCol>
            <VCol cols="4">
              <h3>{{ $t('budgets.cost_total') }}</h3>
              <p>${{ budgetInfo.cost }}</p>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>

      <VCard
        class="pa-4 mb-7"
        :title="t('budgets.products_list')"
      >
        <VCardText>
          <UCTable
            :headers="headers"
            :items="budgetInfo.product_links"
          />
        </VCardText>
      </VCard>

      <VCard
        v-if="existMessage"
        class="pa-4"
        :title="t('budgets.message')"
      >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <p>
                {{ budgetInfo.message }}
              </p>
            </VCol>
          </VRow>
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

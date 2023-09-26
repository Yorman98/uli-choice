<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import BudgetService from '@/services/BudgetService'
import ClientService from '@/services/ClientService'
import type { BudgetInterface } from '@/store/types/BudgetInterface'
import type { ClientInterface } from '@/store/types/ClientInterface'
import { validateRequired } from '@/services/FormValidationService'
import type { ProductLinkInterface } from '@/store/types/ProductLinkInterface'

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
    title: t('navbar.budgets'),
    disabled: true,
  },
])

const openBudget: Ref<boolean> = ref(false)
const isEditBudget: Ref<boolean> = ref(false)
const search: Ref<string> = ref('')

const headers: any[] = [
  { title: t('global.headers.id'), key: 'id' },
  { title: t('global.headers.client'), key: 'user_full_name' },
  { title: t('global.headers.amount'), key: 'price' },
  { title: t('global.headers.date_created'), key: 'created_at' },
  { title: t('global.headers.status'), key: 'statusName' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const status: any[] = [
  { text: t('global.status.pending'), value: 1 },
  { text: t('global.status.processing'), value: 2 },
  { text: t('global.status.completed'), value: 3 },
  { text: t('global.status.cancelled'), value: 4 },
]

const budgetsList: Ref<BudgetInterface[]> = ref([])
const budgetsListFilter: Ref<BudgetInterface[]> = ref([])
const clientList: Ref<ClientInterface[]> = ref([])

const budgetInfo: UnwrapNestedRefs<BudgetInterface> = reactive({
  cost: 0,
  price: 0,
  product_links: [],
  status_id: 1,
  user_id: 0,
} as BudgetInterface)

async function getData() {
  const { data } = await ClientService.getClients()
  const response = data?.data ?? []

  response.forEach((client: ClientInterface) => {
    client.full_name = `${client.first_name} ${client.last_name}`
    clientList.value.push(client)
  })

  const data2 = await BudgetService.getBudgets()
  const response2 = data2.data?.data ?? []

  response2.forEach((budget: BudgetInterface) => {
    if (budget?.user && budget?.user?.length > 0) {
      budget.user_full_name = `${budget?.user[0].first_name} ${budget?.user[0].last_name}`
      budget.user_id = budget.user[0].id
    }

    budget.statusName = t(`global.status.${budget?.status?.name.toLowerCase()}`)
    budgetsList.value.push(budget)
  })

  budgetsList.value.sort((a: BudgetInterface, b: BudgetInterface) => {
    const dateA = new Date(a.created_at)
    const dateB = new Date(b.created_at)

    return dateB - dateA;
  })

  budgetsListFilter.value = budgetsList.value
}

onMounted(() => {
  getData()
})

function closeBudget() {
  openBudget.value = false
  Object.assign(budgetInfo, {
    cost: 0,
    price: 0,
    product_links: [],
    status_id: 1,
    user_id: null,
    id: null,
  })
}

async function saveBudget() {
  if (isEditBudget.value) {
    await BudgetService.updateBudget(budgetInfo)
    isEditBudget.value = false
  }
  else {
    await BudgetService.createBudget(budgetInfo)
  }

  budgetsList.value = []
  await getData()
  closeBudget()
}

function editItem(budget: BudgetInterface) {
  isEditBudget.value = true
  openBudget.value = true
  Object.assign(budgetInfo, budget)
}

async function deleteItem(payload: number) {
  await BudgetService.deleteBudget(payload)
  budgetsList.value = []
  await getData()
}

function goToCreateBudget() {
  if (!isEditBudget.value) {
    Object.assign(budgetInfo, {
      cost: 0,
      price: 0,
      product_links: [{
        url: '',
        quantity: 1,
        cost: 0,
        price: 0,
      }],
      status_id: 1,
      user_id: null,
      id: null,
    })
  }
  openBudget.value = true
}

function updatePrice() {
  let amount = 0
  let cost = 0
  budgetInfo.product_links.forEach((product_link: ProductLinkInterface) => {
    product_link.price = (product_link.cost * product_link.quantity) + (product_link.cost * product_link.quantity) * 0.15
    amount += product_link.price
    cost += product_link.cost * 1
  })
  budgetInfo.price = parseFloat(amount.toFixed(2))
  budgetInfo.cost = parseFloat(cost.toFixed(2))
}

function addProductLink() {
  budgetInfo.product_links.push({
    url: '',
    quantity: 1,
    cost: 0,
    price: 0,
  })
}

function deleteProductLink(index: number) {
  budgetInfo.product_links.splice(index, 1)
}

function filter() {
  if (search.value.trim() !== '')
    budgetsListFilter.value = budgetsList.value.filter((budget: BudgetInterface) => budget.user_full_name?.toLowerCase().includes(search.value.toLocaleLowerCase()))
  else budgetsListFilter.value = budgetsList.value
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.budgets')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle>
          <VRow class="d-flex justify-end">
            <VCol cols="3">
              <VTextField
                prepend-inner-icon="mdi-magnify"
                class="mb-4"
                v-model="search"
                density="compact"
                :label="$t('budgets.search_by_customer')"
                @keyup="filter"
              />
            </VCol>
            <VCol cols="2">
              <VBtn @click="goToCreateBudget">
                <VIcon
                  color="white pr-2"
                  size="35"
                >
                  mdi-plus
                </VIcon>
                <p class="text-button ma-0">
                  {{ t('budgets.create_budget') }}
                </p>
              </VBtn>
            </VCol>
          </VRow>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="budgetsListFilter"
            onlyEdit
            @editItem="editItem"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openBudget"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle>
            <VRow class="align-center ">
              <VCol cols="8">
                <h4 class="text-h4 mb-2 white--text">
                  {{ t('budgets.create_budget') }}
                </h4>
              </VCol>

              <VCol
                cols="4"
                class="d-flex justify-end"
              >
                <h3>
                  {{ t('budgets.price') }} ${{ budgetInfo.price }}
                </h3>
              </VCol>
            </VRow>
          </VCardTitle>

          <VCardText>
            <VSelect
              v-model="budgetInfo.user_id"
              :items="clientList"
              item-title="full_name"
              item-value="id"
              :label="$t('global.headers.client')"
              class="mb-6"
              density="compact"
            />

            <ul class="mb-4">
              <li
                v-for="(productLink, index) in budgetInfo.product_links"
                :key="index"
              >
                <VRow class="align-center">
                  <VCol cols="7">
                    <VTextField
                      v-model="productLink.url"
                      density="compact"
                      :label="$t('global.headers.url')"
                      :rules="[
                        (val) => validateRequired(val) || $t('registration.required_field'),
                      ]"
                    />
                  </VCol>

                  <VCol cols="2">
                    <VTextField
                      v-model="productLink.cost"
                      density="compact"
                      type="number"
                      :label="$t('global.headers.cost')"
                      :rules="[
                        (val) => validateRequired(val) || $t('registration.required_field'),
                      ]"
                      @change="updatePrice"
                    />
                  </VCol>

                  <VCol cols="2">
                    <VTextField
                      v-model="productLink.quantity"
                      density="compact"
                      type="number"
                      :label="$t('global.headers.quantity')"
                      :rules="[
                        (val) => validateRequired(val) || $t('registration.required_field'),
                      ]"
                      @change="updatePrice"
                    />
                  </VCol>

                  <VCol cols="1">
                    <VIcon
                      v-if="budgetInfo.product_links.length > 1"
                      icon="mdi-delete"
                      class=""
                      @click="deleteProductLink(index)"
                    />
                  </VCol>
                </VRow>
              </li>
              <VRow>
                <VCol cols="6">
                  <VBtn
                    class="mb-4"
                    color="primary"
                    @click="addProductLink"
                  >
                    {{ $t('budgets.add_product_link') }}
                  </VBtn>
                </VCol>
              </VRow>
            </ul>

            <VSelect
              v-if="isEditBudget"
              v-model="budgetInfo.status_id"
              :items="status"
              item-title="text"
              item-value="value"
              :label="$t('global.headers.status')"
              class="mb-4"
              density="compact"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closeBudget"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="saveBudget"
            >
              {{ isEditBudget ? $t('global.update') : $t('global.save') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>
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

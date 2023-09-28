<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import BudgetService from '@/services/BudgetService'
import type { BudgetInterface } from '@/store/types/BudgetInterface'
import { validateRequired } from '@/services/FormValidationService'
import { useUserStore } from '@/store/user'

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
    title: t('navbar.budgets'),
    disabled: true,
  },
])

const openBudget: Ref<boolean> = ref(false)
const isEditBudget: Ref<boolean> = ref(false)

const headers: any[] = [
  { title: t('global.headers.id'), key: 'id' },
  { title: t('global.headers.date_created'), key: 'created_at' },
  { title: t('global.headers.status'), key: 'statusName' },
  { title: t('global.headers.amount'), key: 'price', align: 'end' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const budgetsList: Ref<BudgetInterface[]> = ref([])

const budgetInfo: UnwrapNestedRefs<BudgetInterface> = reactive({
  cost: 0,
  price: 0,
  product_links: [{
    url: '',
    quantity: 1,
    cost: 0,
    price: 0,
  }],
  status_id: 1,
  user_id: 0,
} as BudgetInterface)

const userId: Ref<number> = ref(0)

async function getData() {
  const data = await BudgetService.getBudgets()
  const response = data.data?.data ?? []

  response.forEach((budget: BudgetInterface) => {
    budget.statusName = t(`global.status.${budget?.status?.name.toLowerCase()}`)
    budgetsList.value.push(budget)
  })

  budgetsList.value.sort((a: BudgetInterface, b: BudgetInterface) => {
    const dateA = new Date(a.created_at)
    const dateB = new Date(b.created_at)

    return dateB - dateA
  })
}

onMounted(() => {
  const userStore = useUserStore()
  const user = userStore.$state

  userId.value = user.userInfo.id

  getData()
})

function closeBudget() {
  openBudget.value = false
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
    user_id: userId.value,
    id: null,
  })
}

async function saveBudget() {
  if (isEditBudget.value) {
    await BudgetService.updateBudgetFromCustomer(budgetInfo)
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
  budget.user_id = userId.value
  isEditBudget.value = true
  openBudget.value = true
  Object.assign(budgetInfo, budget)
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
      user_id: userId.value,
      id: null,
    })
  }
  openBudget.value = true
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

function goToItem(budget: BudgetInterface) {
  router.push({
    name: 'budgetDetail2',
    params: {
      id: budget.id,
    },
  })
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
          <VRow>
            <VCol
              cols="12"
              class="d-flex justify-end"
            >
              <VBtn
                class="mb-4"
                @click="goToCreateBudget"
              >
                <VIcon
                  color="white pr-2"
                  size="35"
                >
                  mdi-plus
                </VIcon>
                <p class="text-button ma-0">
                  {{ t('budgets.request_budget') }}
                </p>
              </VBtn>
            </VCol>
          </VRow>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="budgetsList"
            edit-and-goto
            @editItem="editItem"
            @goToItem="goToItem"
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
                  {{ isEditBudget ? $t('budgets.edit_budget') : t('budgets.create_budget') }}
                </h4>
              </VCol>
            </VRow>
          </VCardTitle>

          <VCardText>
            <ul class="mb-4">
              <li
                v-for="(productLink, index) in budgetInfo.product_links"
                :key="index"
              >
                <VRow class="align-center">
                  <VCol cols="9">
                    <VTextField
                      v-model="productLink.url"
                      density="compact"
                      :label="$t('global.headers.product_url')"
                      :rules="[
                        (val) => validateRequired(val) || $t('registration.required_field'),
                      ]"
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

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import ClientService from '@/services/ClientService'
import type { ClientInterface } from '@/store/types/ClientInterface'
import { validateMatch, validateRequired } from '@/services/FormValidationService'

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
    title: t('navbar.users'),
    disabled: true,
  },
])

const openClient: Ref<boolean> = ref(false)
const isEditClient: Ref<boolean> = ref(false)
const isPasswordVisible: Ref<boolean> = ref(false)

const headers: any[] = [
  { title: t('global.headers.name'), key: 'first_name' },
  { title: t('global.headers.last_name'), key: 'last_name' },
  { title: t('global.headers.email'), key: 'email' },
  { title: t('global.headers.phone_number'), key: 'phone_number' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const role: any[] = [
  { text: t('global.roles.admin'), value: 'admin' },
  { text: t('global.roles.client'), value: 'client' },
]

const clientList: Ref<ClientInterface[]> = ref([])

const clientInfo: UnwrapNestedRefs<ClientInterface> = reactive({
  first_name: '',
  last_name: '',
  email: '',
  phone_number: '',
  password: '',
  role: '',
} as ClientInterface)

async function getData() {
  const { data } = await ClientService.getClients()
  const response = data?.data ?? []

  response.forEach((client: ClientInterface) => {
    clientList.value.push(client)
  })
}

onMounted(() => {
  getData()
})

function closeClient() {
  openClient.value = false
  Object.assign(clientInfo, {
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    password: '',
    role: '',
    id: null,
  })
}

async function saveClient() {
  if (isEditClient.value) {
    await ClientService.updateClient(clientInfo)
    isEditClient.value = false
  }
  else {
    await ClientService.createClient(clientInfo)
  }

  clientList.value = []
  await getData()
  closeClient()
}

function editItem(client: ClientInterface) {
  isEditClient.value = true
  openClient.value = true
  Object.assign(clientInfo, client)
}

async function deleteItem(payload: number) {
  await ClientService.deleteClient(payload)
  clientList.value = []
  await getData()
}

function goToCreateClient() {
  if (!isEditClient.value) {
    Object.assign(clientInfo, {
      first_name: '',
      last_name: '',
      email: '',
      phone_number: '',
      password: '',
      role: '',
      id: null,
    })
  }
  openClient.value = true
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.users')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="goToCreateClient">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0">
              {{ t('users.create_user') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="clientList"
            onlyEdit
            @editItem="editItem"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openClient"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle color="red">
            <h4 class="text-h4 mb-2 white--text">
              {{ t('users.create_user') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VSelect
              v-if="clientInfo.id === null"
              v-model="clientInfo.role"
              :items="role"
              item-title="text"
              item-value="value"
              :label="$t('global.headers.type_user')"
              class="mb-6"
              density="compact"
            />

            <VTextField
              v-model="clientInfo.first_name"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.name')"
              :rules="[
                (val) => validateRequired(val) || $t('registration.required_field'),
              ]"
            />

            <VTextField
              v-model="clientInfo.last_name"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.last_name')"
              :rules="[
                (val) => validateRequired(val) || $t('registration.required_field'),
              ]"
            />

            <VTextField
              v-model="clientInfo.email"
              class="mb-6"
              density="compact"
              type="email"
              :label="$t('global.headers.email')"
              :rules="[
                (val) => validateRequired(val) || $t('registration.required_field'),
                (val) => validateMatch(val, 'email') || $t('registration.incorrect_email'),
              ]"
            />

            <VTextField
              v-if="clientInfo.id === null"
              v-model="clientInfo.password"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.password')"
              :type="isPasswordVisible ? 'text' : 'password'"
              :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
              :rules="[
                (val) => validateRequired(val) || $t('registration.required_field'),
                (val) => validateMatch(val, 'password') || $t('registration.incorrect_password'),
              ]"
              @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />

            <VTextField
              v-model="clientInfo.phone_number"
              density="compact"
              :label="$t('global.headers.phone_number')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closeClient"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="saveClient"
            >
              {{ isEditClient ? $t('global.update') : $t('global.save') }}
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
</style>

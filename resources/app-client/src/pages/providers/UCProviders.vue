<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapNestedRefs } from 'vue'
import { ref } from 'vue'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import ProviderService from '@/services/ProviderService'
import type { ProviderInterface } from '@/store/types/ProviderInterface'
import { validateRequired } from '@/services/FormValidationService'

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
    title: t('navbar.providers'),
    disabled: true,
  },
])

const openProvider: Ref<boolean> = ref(false)
const isEditProvider: Ref<boolean> = ref(false)

const headers: any[] = [
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.website'), key: 'website' },
  { title: t('global.headers.phone_number'), key: 'phone_number' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const providersList: Ref<ProviderInterface[]> = ref([])

const providerInfo: UnwrapNestedRefs<ProviderInterface> = reactive({
  name: '',
  website: '',
  phone_number: '',
} as ProviderInterface)

async function getData() {
  const { data } = await ProviderService.getProviders()
  const response = data.providers?.data ?? []

  response.forEach((provider: ProviderInterface) => {
    providersList.value.push(provider)
  })
}

onMounted(() => {
  getData()
})

function closeProvider() {
  openProvider.value = false
  Object.assign(providerInfo, {
    name: '',
    website: '',
    phone_number: '',
    id: null,
  })
}

async function saveProvider() {
  if (isEditProvider.value) {
    await ProviderService.updateProvider(providerInfo)
    isEditProvider.value = false
  }
  else {
    await ProviderService.createProvider(providerInfo)
  }

  providersList.value = []
  await getData()
  closeProvider()
}

function editItem(provider: ProviderInterface) {
  isEditProvider.value = true
  openProvider.value = true
  Object.assign(providerInfo, provider)
}

async function deleteItem(payload: number) {
  await ProviderService.deleteProvider(payload)
  providersList.value = []
  await getData()
}

function goToCreateProvider() {
  if (!isEditProvider.value) {
    Object.assign(providerInfo, {
      name: '',
      website: '',
      phone_number: '',
      id: null,
    })
  }
  openProvider.value = true
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('navbar.providers')"
        :path="path"
      />

      <VCard class="pa-4">
        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="goToCreateProvider">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0">
              {{ t('providers.create_provider') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="providersList"
            onlyEdit
            @editItem="editItem"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <VDialog
        v-model="openProvider"
        max-width="700px"
      >
        <VCard class="pa-4">
          <VCardTitle color="red">
            <h4 class="text-h4 mb-2 white--text">
              {{ t('providers.create_provider') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VTextField
              v-model="providerInfo.name"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.name')"
              :rules="[
                (val) => validateRequired(val) || $t('registration.required_field'),
              ]"
            />

            <VTextField
              v-model="providerInfo.website"
              class="mb-6"
              density="compact"
              :label="$t('global.headers.website')"
            />

            <VTextField
              v-model="providerInfo.phone_number"
              density="compact"
              :label="$t('global.headers.phone')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              outlined
              @click="closeProvider"
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              flat
              @click="saveProvider"
            >
              {{ isEditProvider ? $t('global.update') : $t('global.save') }}
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

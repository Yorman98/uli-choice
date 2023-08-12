<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'

const { t } = useI18n()

const router = useRouter()

const path: any[] = [
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
]

const headers: any[] = [
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.website'), key: 'website' },
  { title: t('global.headers.phone_number'), key: 'phone_number' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const providers: any[] = ref([])

onMounted(() => {
  getData()
})

function getData() {
  // AQUI VA EL ENDPOINT DE TRAER LOS PROVEEDORES
  providers.value = [
    {
      id: 1,
      name: 'Shein',
      website: 'https://us.shein.com/',
      phone_number: '+58123456789',
    },
    {
      id: 2,
      name: 'Amazon',
      website: 'https://www.amazon.com/',
      phone_number: '+58123456789',
    },
  ]
}

function editItem(payload: any) {
  router.push({
    name: 'editProviders',
    params: {
      id: payload.id,
    },
  })
}

function deleteItem(payload: number) {
  // AQUI VA EL ENDPOINT DE ELIMINAR
  getData()
}

function goToCreateProvider() {
  router.push({
    name: 'createProviders',
  })
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
            :items="providers"
            @editItem="editItem"
            @deleteItem="deleteItem"
          />
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
</style>

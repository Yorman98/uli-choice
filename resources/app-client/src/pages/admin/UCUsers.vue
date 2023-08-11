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
    title: t('navbar.users'),
    disabled: true,
  },
]

const headers: any[] = [
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.last_name'), key: 'last_name' },
  { title: t('global.headers.email'), key: 'email' },
  { title: t('global.headers.phone_number'), key: 'phone_number' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const attributes: any[] = [
  {
    id: 1,
    name: 'Angel',
    last_name: 'Pico',
    email: 'angel.j.pico@gmail.com',
    phone_number: '+584120738575',
  },
  {
    id: 2,
    name: 'Josue',
    last_name: 'Arellano',
    email: 'angel.j.pico@gmail.com',
    phone_number: '+584120738575',
  },
]

function editItem(payload: any) {
  router.push({
    name: 'editUsers',
    params: {
      id: payload.id,
    },
  })
}

function deleteItem(payload: number) {
  console.log('eliminar', payload)
}

function goToCreateUser() {
  router.push({
    name: 'createUsers',
  })
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
          <VBtn @click="goToCreateUser">
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
            :items="attributes"
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

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

const path: any[] = ref([
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('navbar.providers'),
    disabled: false,
    to: {
      name: 'providers',
    },
  },
  {
    title: t('providers.create_provider'),
    disabled: true,
  },
])

const providerData = {
  name: '',
  website: '',
  phone_number: '',
}

const providerDataLocal = ref(structuredClone(providerData))

const id: any = ref(null)

onMounted(() => {
  id.value = `${route.params.id}`
  console.log(id)
  console.log(providerData)
  if (id.value !== 'undefined') {
    getProviderData(id)
    updatePath()
  }
  else console.log('crear')
})

function getProviderData(id: any) {
  console.log('Get provider', id.value)
  providerData.value = {
    name: 'Shein',
    website: 'https://us.shein.com/',
    phone_number: '+58123456789',
  }
}

function updatePath() {
  path.value[2].title = t('providers.edit_provider')
}

function saveUser() {
  console.log('CREAR', providerDataLocal.value)
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
        <VCardText>
          <VForm class="mt-6">
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="providerDataLocal.name"
                  placeholder="John"
                  label="First Name"
                />
              </VCol>

              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="providerDataLocal.website"
                  placeholder="Doe"
                  label="Website"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="providerDataLocal.phone_number"
                  label="Phone Number"
                  placeholder="+1 (917) 543-9876"
                />
              </VCol>

              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4"
              >
                <VBtn @click="saveUser">
                  Save changes
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
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
letlet

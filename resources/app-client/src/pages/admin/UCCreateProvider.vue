<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import { validateRequired } from '@/services/FormValidationService'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

const isProgressRegister = ref(false)

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

const form = ref(structuredClone(providerData))

const id: any = ref(null)

onMounted(() => {
  id.value = `${route.params.id}`
  if (id.value !== 'undefined') {
    getProviderData(id)
    updatePath()
  }
})

const validateForm = () => {
  const { name, website } = form.value

  return validateRequired(name)
    && validateRequired(website)
}

function getProviderData(id: any) {
  //AQUI VA EL ENDPOINT PARA TRAERME EL PROVEEDOR
  form.value = {
    name: 'Shein',
    website: 'https://us.shein.com/',
    phone_number: '+58123456789',
  }
}

function updatePath() {
  path.value[2].title = t('providers.edit_provider')
}

function saveProvider() {
  //AQUI VA EL ENDPOINT PARA GUARDAR O ACTUALIZAR EL USUARIO
  isProgressRegister.value = true
  router.push({
    name: 'providers',
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
        <VCardText>
          <VForm
            class="mt-6"
            @submit.prevent="saveProvider"
          >
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="form.name"
                  :label="$t('global.name')"
                  :rules="[
                    (val) => validateRequired(val) || $t('registration.required_field'),
                  ]"
                />
              </VCol>

              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="form.website"
                  :label="$t('global.website')"
                  :rules="[
                    (val) => validateRequired(val) || $t('registration.required_field'),
                  ]"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="form.phone_number"
                  :label="$t('global.phone')"
                />
              </VCol>

              <VCol
                cols="12"
                class="d-flex flex-wrap gap-4"
              >
                <VBtn
                  type="submit"
                  :loading="isProgressSave"
                  :disabled="!validateForm()"
                >
                  {{ $t(id !== 'undefined' ? 'global.update' : 'global.save') }}
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

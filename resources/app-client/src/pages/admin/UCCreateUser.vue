<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import { validateMatch, validateRequired } from '@/services/FormValidationService'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

const isPasswordVisible = ref(false)
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
    title: t('navbar.users'),
    disabled: false,
    to: {
      name: 'users',
    },
  },
  {
    title: t('users.create_user'),
    disabled: true,
  },
])

const accountData = {
  first_name: '',
  last_name: '',
  email: '',
  phone_number: '',
  password: '',
}

const form = ref(structuredClone(accountData))

const id: any = ref(null)

onMounted(() => {
  id.value = `${route.params.id}`
  if (id.value !== 'undefined') {
    getUserData(id)
    updatePath()
  }
})

const validateForm = () => {
  const { first_name, last_name, email, password } = form.value

  return validateRequired(first_name)
    && validateRequired(last_name)
    && validateRequired(email)
    && validateMatch(email, 'email')
    && validateRequired(password)
    && validateMatch(password, 'password')
}

function getUserData(id: any) {
  //AQUI VA EL ENDPOINT PARA TRAERME EL USUARIO
  form.value = {
    first_name: 'Angel',
    last_name: 'Pico',
    email: 'angel@email.com',
    phone_number: '+58123456789',
    password: 'aurinegro',
  }
}

function updatePath() {
  path.value[2].title = t('users.edit_user')
}

function saveUser() {
  //AQUI VA EL ENDPOINT PARA GUARDAR O ACTUALIZAR EL USUARIO
  isProgressRegister.value = true
  router.push({
    name: 'users',
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
        <VCardText>
          <VForm class="mt-6" @submit.prevent="saveUser">
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="form.first_name"
                  :label="$t('global.firstname')"
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
                  v-model="form.last_name"
                  :label="$t('global.lastname')"
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
                  v-model="form.email"
                  :label="$t('global.email')"
                  type="email"
                  :rules="[
                    (val) => validateRequired(val) || $t('registration.required_field'),
                    (val) => validateMatch(val, 'email') || $t('registration.incorrect_email'),
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
                md="6"
              >
                <VTextField
                  v-model="form.password"
                  :label="$t('global.password')"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  :rules="[
                    (val) => validateRequired(val) || $t('registration.required_field'),
                    (val) => validateMatch(val, 'password') || $t('registration.incorrect_password'),
                  ]"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
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

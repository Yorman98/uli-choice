<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'

const { t } = useI18n()

const router = useRouter()
const route = useRoute()

const isPasswordVisible = ref(false)
const isEdit = ref(false)

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

const accountDataLocal = ref(structuredClone(accountData))

const id: any = ref(null)

onMounted(() => {
  id.value = `${route.params.id}`
  console.log(id)
  console.log(accountData)
  if (id.value !== 'undefined') {
    getUserData(id)
    updatePath()
  }
  else console.log('crear')
})

function getUserData(id: any) {
  console.log('Get user', id.value)
  accountDataLocal.value = {
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
  console.log('CREAR', accountDataLocal.value)
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
          <VForm class="mt-6">
            <VRow>
              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="accountDataLocal.first_name"
                  placeholder="John"
                  label="First Name"
                />
              </VCol>

              <VCol
                md="6"
                cols="12"
              >
                <VTextField
                  v-model="accountDataLocal.last_name"
                  placeholder="Doe"
                  label="Last Name"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.email"
                  label="E-mail"
                  placeholder="johndoe@gmail.com"
                  type="email"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.phone_number"
                  label="Phone Number"
                  placeholder="+1 (917) 543-9876"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model="accountDataLocal.password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  label="Password"
                  placeholder="············"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
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

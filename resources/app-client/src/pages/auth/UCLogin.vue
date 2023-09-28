<script setup lang="ts">
import logo from '@images/logo-uli.png'
import { useUserStore } from '@/store/user'
import router from '@/router'
import { validateRequired } from '@/services/FormValidationService'
import { ADMIN } from '@/utils/constants'

const userStore = useUserStore()
const isProgressLogin = ref(false)
const isErrorLogin = ref(false)
const errorMessage = ref('')

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const checkLogin = async () => {
  isErrorLogin.value = false
  isProgressLogin.value = true

  try {
    await userStore.login({
      email: form.value.email,
      password: form.value.password,
    })

    await userStore.loadUser()

    if (userStore.getUserInfo.role === ADMIN)
      router.push('/dashboard')
    else
      router.push('/products')

  }
  catch (error) {
    isProgressLogin.value = false
    isErrorLogin.value = true
    errorMessage.value = error.response.data.message
  }
}

const validateForm = () => {
  const { email, password } = form.value

  return validateRequired(email) && validateRequired(password)
}

const isPasswordVisible = ref(false)
</script>

<template>
  <div class="auth-wrapper d-flex flex-column align-center justify-center pa-4">
    <VCard
      v-if="isErrorLogin"
      class="auth-error pa-3 mb-8 w-100 rounded-0"
      max-width="448"
    >
      <VCardItem class="justify-start pa-0">
        <span class="text-grey-darken-3">{{ $t('global.error') }}</span>
        <span class="text-grey-darken-3">{{ errorMessage }}</span>
      </VCardItem>
    </VCard>
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <VImg
          class="bg-white"
          width="120"
          :aspect-ratio="1"
          :src="logo"
        />
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          {{ $t('global.welcome') }} 👋🏻
        </h5>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="checkLogin">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                :label="$t('global.headers.email')"
                type="email"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                ]"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                :label="$t('global.headers.password')"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                ]"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- login button -->
              <VBtn
                class="mt-6 mb-3"
                block
                type="submit"
                :loading="isProgressLogin"
                :disabled="!validateForm()"
              >
                {{ $t('registration.login') }}
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>{{ $t('registration.new_register_message') }}</span>
              <RouterLink
                class="text-primary ms-2"
                to="/register"
              >
                {{ $t('registration.sign_up') }}
              </RouterLink>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss" scoped>
@use "@core/scss/template/pages/page-auth";

.auth-wrapper {
  .auth-error {
    background: #FFCDD2;
    border-left: 2px solid red;
  }
}
</style>

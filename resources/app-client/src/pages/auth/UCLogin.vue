<script setup lang="ts">
import logo from '@images/logo-uli.png'
import { useUserStore } from '@/store/user'
import router from '@/router'
import { validateMatch, validateRequired } from '@/services/FormValidationService'

const userStore = useUserStore()

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const checkLogin = async () => {
  await userStore.login({
    email: form.value.email,
    password: form.value.password,
  })

  // redirect to dashboard
  router.push('/')
}

const isPasswordVisible = ref(false)
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
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
        ></VImg>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Welcome! 👋🏻
        </h5>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="checkLogin">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                autofocus
                placeholder="example@email.com"
                :label="$t('global.email')"
                type="email"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                  (val) => validateMatch(val, 'email') || $t('registration.incorrect_email'),
                ]"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                :label="$t('global.password')"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field')
                ]"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- login button -->
              <VBtn
                class="mt-6 mb-3"
                block
                type="submit"
                :disabled="!validateRequired(form.email) || !validateMatch(form.email, 'email') || !validateRequired(form.password)"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>New on our platform?</span>
              <RouterLink
                class="text-primary ms-2"
                to="/register"
              >
                Create an account
              </RouterLink>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>

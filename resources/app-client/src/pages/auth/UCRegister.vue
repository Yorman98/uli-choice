<script setup lang="ts">
import logo from '@images/logo-uli.png'
import { validateMatch, validateRequired } from '@/services/FormValidationService'
import { useUserStore } from '@/store/user'
import router from '@/router'

const userStore = useUserStore()
const isProgressRegister = ref(false)

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone_number: '',
  password: '',
})

const checkRegistration = async () => {
  isProgressRegister.value = true

  await userStore.register({
    first_name: form.value.first_name,
    last_name: form.value.last_name,
    email: form.value.email,
    phone_number: form.value.phone_number,
    password: form.value.password,
  })

  router.push('/login')
}

const validateForm = () => {
  const { first_name, last_name, email, password } = form.value

  return validateRequired(first_name)
    && validateRequired(last_name)
    && validateRequired(email)
    && validateMatch(email, 'email')
    && validateRequired(password)
    && validateMatch(password, 'password')
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
        />
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          {{ $t('global.welcome') }}
        </h5>
        <p class="mb-0">
          {{ $t('registration.register_message') }}
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="checkRegistration">
          <VRow>
            <!-- Username -->
            <VCol cols="12">
              <VTextField
                v-model="form.first_name"
                :label="$t('global.headers.name')"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                ]"
              />
            </VCol>

            <!-- Lastname -->
            <VCol cols="12">
              <VTextField
                v-model="form.last_name"
                :label="$t('global.headers.last_name')"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                ]"
              />
            </VCol>

            <!-- Phone -->
            <VCol cols="12">
              <VTextField
                v-model="form.phone_number"
                :label="$t('global.headers.phone_number')"
              />
            </VCol>

            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                :label="$t('global.headers.email')"
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
                :label="$t('global.headers.password')"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                :rules="[
                  (val) => validateRequired(val) || $t('registration.required_field'),
                  (val) => validateMatch(val, 'password') || $t('registration.incorrect_password'),
                ]"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />

              <!-- registration button -->
              <VBtn
                class="mt-6 mb-3"
                block
                type="submit"
                :loading="isProgressRegister"
                :disabled="!validateForm()"
              >
                {{ $t('registration.register') }}
              </VBtn>
            </VCol>

            <!-- login instead -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>{{ $t('registration.login_message') }}</span>
              <RouterLink
                class="text-primary ms-2"
                to="/login"
              >
                {{ $t('registration.login') }}
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

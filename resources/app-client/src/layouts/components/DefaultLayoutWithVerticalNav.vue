<script lang="ts" setup>

import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'

// Components
// import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <!-- 👉 Search -->
        <div
          class="d-flex align-center cursor-pointer"
          style="user-select: none;"
        >
          <!-- 👉 Search Trigger button -->
          <IconBtn>
            <VIcon icon="bx-search" />
          </IconBtn>

          <span class="d-none d-md-flex align-center text-disabled">
            <span class="me-3">Search</span>
          </span>
        </div>

        <VSpacer />

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
      <VerticalNavLink
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/dashboard',
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('navbar.users'),
          icon: 'mdi-account-multiple-outline',
          to: {
            name: 'users',
          },
        }"
      />

      <!-- 👉 Products -->
      <VerticalNavSectionTitle
        :item="{
          heading: $t('navbar.products'),
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('navbar.attributes_groups'),
          icon: 'mdi-tag-multiple-outline',
          to: {
            name: 'attributesGroups',
          },
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('navbar.categories'),
          icon: 'mdi-tag-outline',
          to: {
            name: 'categories',
          },
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('navbar.products'),
          icon: 'mdi-hanger',
          to: {
            name: 'product-list',
          },
        }"
      />

      <!-- 👉 Purchases -->
      <VerticalNavSectionTitle
        :item="{
          heading: $t('navbar.purchases'),
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('navbar.providers'),
          icon: 'mdi-account-tag-outline',
          to: {
            name: 'providers',
          },
        }"
      />
      <VerticalNavLink
        :item="{
          title: $t('purchases.store_purchases'),
          icon: 'mdi-shopping-outline',
          to: {
            name: 'purchase',
          },
        }"
      />

      <!-- 👉 Options theme -->
      <VerticalNavSectionTitle
        :item="{
          heading: $t('navbar.configuration'),
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Login',
          icon: 'bx-log-in',
          to: '/login',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Register',
          icon: 'bx-user-plus',
          to: '/register',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Error',
          icon: 'bx-info-circle',
          to: '/no-existence',
        }"
      />
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <!--
      <template #footer>
      <Footer />
      </template>
    -->
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>

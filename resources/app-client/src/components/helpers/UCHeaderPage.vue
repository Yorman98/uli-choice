<script setup lang="ts">
defineProps({
  title: String,
  path: Array,
})
</script>

<template>
  <VRow class="uc-header-page">
    <VCol cols="12">
      <VCard class="pa-5">
        <VCardTitle>
          <h4 class="text-h4 mb-3">
            {{ title }}
          </h4>
          <VBreadcrumbs
            v-if="path"
            class="pa-0"
            :items="path"
          >
            <template #divider>
              <VIcon icon="mdi-chevron-right" />
            </template>
          </VBreadcrumbs>
        </VCardTitle>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">
.uc-header-page {
  h4 {
    font-size: 30px !important;
    line-height: 1;
  }

  .v-breadcrumbs {
    font-size: 16px !important;

    :deep(.v-breadcrumbs-item) {
      padding: 0 2px !important;
    }
  }

  :deep(.v-breadcrumbs-divider) {
    padding: 0 !important;
  }
}
</style>

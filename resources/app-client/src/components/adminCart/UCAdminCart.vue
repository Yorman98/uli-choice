<script setup lang="ts">
defineProps({
  clientId: Number,
})
</script>

<template>
  <VCard
    class="mb-6"
    :title="$t('sales.order_details')"
  >
    <VCardTitle class="d-flex justify-end mb-4">
      <VRow>
        <VCol
          cols="12"
          class="d-flex justify-end flex-column"
        >
          {{ clientId }}
        </VCol>
      </VRow>
    </VCardTitle>
  </VCard>
</template>

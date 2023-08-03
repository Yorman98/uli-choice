<script setup lang="ts">
defineProps({
  title: String,
  path: Array
});
</script>

<template>
  <VRow>
  <VCol cols="12">
    <VCard class="pa-4">
      <VCardTitle>

        <h3 class="text-h4 mb-4">{{ title }}</h3>

        <v-breadcrumbs
          class="pl-4"
          :items="path"
        >
          <template v-slot:divider>
            <v-icon icon="mdi-chevron-right"></v-icon>
          </template>
        </v-breadcrumbs>

      </VCardTitle>
    </VCard>
  </VCol>
  </VRow>
</template>

<style scoped lang="scss">

</style>

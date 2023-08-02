<script setup lang="ts">
import { ref, Ref } from "vue";

defineProps({
  isEdit: {
    type: Boolean,
    default: false,
  },
  attribute: {
    type: Object,
    default: () => {},
  },
})

defineExpose({ openDialog })

const emit = defineEmits(['closeDialog', 'saveAttributeData'])

const dialog: Ref<boolean> = ref(false)

function openDialog() {
  dialog.value = true
}

function closeDialog() {
  dialog.value = false
  emit('closeDialog')
}

function saveAttributeData() {
  dialog.value = false
  emit('saveAttributeData')
}
</script>

<template>
  <VDialog
      v-model="dialog"
      max-width="700px"
  >
    <VCard class="pa-4">
      <VCardTitle>
        <h4 class="text-h4 mb-4">
          {{ isEdit ? $t('products.attributes.edit_attribute') : $t('products.attributes.create_attribute') }}
        </h4>
      </VCardTitle>

      <VCardText>
        <VTextField
            v-model="attribute.name"
            :placeholder="$t('products.attributes.attribute_name')"
            :label="$t('products.attributes.attribute_name')"
        />
      </VCardText>

      <VCardActions class="d-flex justify-end">
        <VBtn
            color="primary"
            outlined
            @click="closeDialog"
        >
          {{ $t('global.cancel') }}
        </VBtn>

        <VBtn
            color="primary"
            flat
            @click="saveAttributeData"
        >
          {{ isEdit ? $t('global.update') : $t('global.save') }}
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<style scoped lang="scss">

</style>

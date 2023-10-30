<script setup lang="ts">
import { reactive, ref, Ref } from 'vue'
import { AttributeInterface, AttributeGroupInterface} from '@/store/types/AttributeInterface'

const props = defineProps(['isEdit', 'isGroup'])

defineExpose({ openDialog, closeDialog })

const emit = defineEmits(['closeDialog', 'saveData'])

const dialog: Ref<boolean> = ref(false)

let attribute: AttributeInterface | AttributeGroupInterface = reactive({
  name: ''
})

const groupType: String[] = ['Select', 'Radio']

function openDialog(payload: any = {}) {
  if (Object.keys(payload).length > 0) {
    Object.assign(attribute, payload)
  }
  dialog.value = true
}

function closeDialog() {
  if (props.isGroup) {
    Object.assign(attribute, {
      name: '',
      group_type: '',
    })
  } else {
    Object.assign(attribute, {
      name: '',
    })
  }
  dialog.value = false
}

function saveData() {
  emit('saveData', attribute)
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
          class="mb-8"
          :placeholder="$t('products.attributes.attribute_name')"
          :label="$t('products.attributes.attribute_name')"
        />
        <VSelect
          v-if="props.isGroup"
          v-model="attribute.group_type"
          :items="groupType"
          :placeholder="$t('products.attributes.attribute_type')"
          :label="$t('products.attributes.attribute_type')"
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
          @click="saveData"
        >
          {{ isEdit ? $t('global.update') : $t('global.save') }}
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<style scoped lang="scss">
h4 {
  font-size: 30px !important;
  line-height: 1;
}
</style>

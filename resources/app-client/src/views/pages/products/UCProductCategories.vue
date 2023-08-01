<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { ref, Ref } from 'vue';
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCEditTable from "@/components/helpers/UCEditTable.vue";

const { t } = useI18n();
const headers: any[] =  [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false }
];
const attributes: Object[] = [
  {
    id: 1,
    name: 'Talla'
  },
  {
    id: 2,
    name: 'Color'
  }
];
const path: Object[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard'
    },
  },
  {
    title:  t('products.attributesGroup.title'),
    disabled: true
  }
];
let dialog: Ref<boolean> = ref(false);
let isEdit: Ref<boolean> = ref(false);
let attribute: Object = {};
function editItem (payload: Object) {
  attribute = payload;
  isEdit.value = true;
  dialog.value = true;
}

function deleteItem (payload: number) {
  console.log(payload);
}

function openDialog () {
  dialog.value = true;
}
function closeDialog () {
  dialog.value = false;
  isEdit.value = false;
  attribute = {};
}

function saveAttributeData () {
  console.log(attribute);
  closeDialog();
}
</script>

<template>
  <VRow>
    <VCol cols="12">

      <UCHeaderPage
        class="mb-5"
        :title="t('products.attributesGroup.title')"
        :path="path"
      />

      <VCard class="pa-4">

        <VCardTitle class="d-flex justify-end mb-4">
          <VBtn @click="openDialog">
            <VIcon
              color="white pr-2"
              size="35"
            >
              mdi-plus
            </VIcon>
            <p class="text-button ma-0">
              {{ t('products.attributesGroup.create_attribute') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCEditTable
            :headers="headers"
            :items="attributes"
            @editItem="editItem"
            @deleteItem="deleteItem"
          />
        </VCardText>

      </VCard>

      <VDialog
        v-model="dialog"
        activator="parent"
        max-width="700px"
        persistent
      >
        <VCard class="pa-4">

          <VCardTitle>
            <h4 class="text-h4 mb-4">
              {{ isEdit ? $t('products.attributesGroup.edit_attribute') : $t('products.attributesGroup.create_attribute') }}
            </h4>
          </VCardTitle>

          <VCardText>
            <VTextField
              v-model="attribute.name"
              :placeholder="$t('products.attributesGroup.attribute_name')"
              :label="$t('products.attributesGroup.attribute_name')"
            />
          </VCardText>

          <VCardActions class="d-flex justify-end">
            <VBtn
              color="primary"
              @click="closeDialog"
              outlined
            >
              {{ $t('global.cancel') }}
            </VBtn>

            <VBtn
              color="primary"
              @click="saveAttributeData"
              flat
            >
              {{ isEdit ? $t('global.update') : $t('global.save') }}
            </VBtn>
          </VCardActions>
        </VCard>
      </VDialog>
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

<script setup lang="ts">
import { useI18n } from "vue-i18n"
import { ref, Ref } from "vue"
import UCHeaderPage from "@/components/helpers/UCHeaderPage.vue"
import UCTable from "@/components/helpers/UCTable.vue"
import { AttributeInterface } from "@/store/types/AttributeInterface"
import UCCreateAttributeDialog from '@/pages/attributes/components/UCCreateAttributeDialog.vue'

const { t } = useI18n()

const attributeDialog = ref(null)

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('products.attributes.attributes_group_title'),
    disabled: false,
    to: {
      name: 'attributesGroups',
    },
  },
  {
    title: t('products.attributes.attributes_group_title'),
    disabled: true,
  },
]

const isEdit: Ref<boolean> = ref(false)

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const attributes: AttributeInterface[] = [
  {
    id: 1,
    name: 'Amarillo',
    attributeGroupId: 1,
  },
  {
    id: 2,
    name: 'Rojo',
    attributeGroupId: 1,
  },
  {
      id: 3,
      name: 'Verde',
      attributeGroupId: 1,
  },
  {
      id: 4,
      name: 'Azul',
      attributeGroupId: 1,
  },
]

let attribute: AttributeInterface = {
  id: 0,
  name: '',
  attributeGroupId: 0,
}

function editItem(payload: AttributeInterface) {
  attribute = payload
  isEdit.value = true
    attributeDialog.value.openDialog()
}

function deleteItem(payload: number) {
  console.log(payload)
}

function closeDialog() {
    isEdit.value = false
    attribute = {
      id: 0,
      name: '',
      attributeGroupId: 0,
    }
}

function saveAttributeData() {
    console.log(attribute)
    closeDialog()
}
</script>

<template>
  <div>
    <UCHeaderPage
      class="mb-5"
      :title="$t('products.attributes.attribute_list_title')"
      :path="path"
    />

    <VCard class="pa-4">
      <VCardTitle class="d-flex justify-end mb-4">
        <VBtn @click="attributeDialog.openDialog()">
          <VIcon
            color="white pr-2"
            size="35"
          >
            mdi-plus
          </VIcon>
          <p class="text-button ma-0">
            {{ t('products.attributes.create_attribute') }}
          </p>
        </VBtn>
      </VCardTitle>

      <VCardText>
        <UCTable
          :headers="headers"
          :items="attributes"
          :hasSubItems="false"
          @editItem="editItem"
          @deleteItem="deleteItem"
        />
      </VCardText>
    </VCard>

    <UCCreateAttributeDialog
      ref="attributeDialog"
      :isEdit="isEdit"
      :attribute="attribute"
      @closeDialog="closeDialog"
      @saveData="saveAttributeData"
    />
  </div>
</template>

<style scoped lang="scss">
.v-btn {
  span.v-btn__content p {
    color: #FFFFFF;
  }
}
</style>

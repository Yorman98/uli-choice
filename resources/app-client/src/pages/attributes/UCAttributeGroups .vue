<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router';
import { ref, Ref } from "vue"
import type { AttributeGroupInterface } from '@/store/interfaces/AttributeInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import UCCreateAttributeDialog from '@/pages/attributes/components/UCCreateAttributeDialog.vue'

const { t } = useI18n()

const router = useRouter()

const attributeDialog = ref(null)

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const attributes: AttributeGroupInterface[] = [
  {
    id: 1,
    name: 'Talla',
    groupType: 'text',
  },
  {
    id: 2,
    name: 'Color',
    groupType: 'text',
  },
]

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
    disabled: true,
  },
]

const isEdit: Ref<boolean> = ref(false)

let attribute: AttributeGroupInterface = {
  id: 0,
  name: '',
  groupType: '',
}

function editItem(payload: AttributeGroupInterface) {
  attribute = payload
  isEdit.value = true
  attributeDialog.value.openDialog()
}

function goToGroup(payload: number) {
  router.push({
    name: 'attributesList',
    params: {
      id: payload,
    },
  })
}

function deleteItem(payload: number) {
  console.log(payload)
}

function closeDialog() {
  isEdit.value = false
  attribute = {
    id: 0,
    name: '',
    groupType: '',
  }
}

function saveAttributeData() {
  console.log(attribute)
  closeDialog()
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-5"
        :title="$t('products.attributes.attributes_group_title')"
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
              {{ t('products.attributes.create_group_attribute') }}
            </p>
          </VBtn>
        </VCardTitle>

        <VCardText>
          <UCTable
            :headers="headers"
            :items="attributes"
            :hasSubItems="true"
            @editItem="editItem"
            @goToItem="goToGroup"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <UCCreateAttributeDialog
        ref="attributeDialog"
        :isEdit="isEdit"
        :attribute="attribute"
        @closeDialog="closeDialog"
        @saveAttributeData="saveAttributeData"
      />
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

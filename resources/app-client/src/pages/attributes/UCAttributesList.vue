<script setup lang="ts">
import { useI18n } from "vue-i18n"
import { ref, Ref, reactive } from "vue"
import UCHeaderPage from "@/components/helpers/UCHeaderPage.vue"
import UCTable from "@/components/helpers/UCTable.vue"
import { AttributeInterface } from "@/store/types/AttributeInterface"
import UCCreateAttributeDialog from '@/pages/attributes/components/UCCreateAttributeDialog.vue'
import AttributeService from "@/services/AttributeService";
import { useRoute } from 'vue-router'

const { t } = useI18n()

const route = useRoute()

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

const attributes: Ref<AttributeInterface[]> = ref([])

let attribute: AttributeInterface = reactive({})

onMounted(async () => {
  await initData()
})

async function initData() {
  const response = await AttributeService.getAttributesGroupById(route.params.id)
  attributes.value = response.data.attributeGroup.attributes
}

function editItem(payload: AttributeInterface) {
  attribute = Object.assign(payload)
  isEdit.value = true
  attributeDialog.value.openDialog(attribute)
}

async function deleteItem(payload: number) {
  await AttributeService.deleteAttribute(payload)
  await initData()
}

function closeDialog() {
  isEdit.value = false
  attribute = Object.assign({ name: '' })
  attributeDialog.value.closeDialog()
}

async function saveAttributeData(payload: AttributeInterface) {
  if(isEdit.value) {
    await AttributeService.updateAttribute({
      id: payload.id,
      name: payload.name,
      attribute_group_id: route.params.id
    })
  } else {
    await AttributeService.createAttribute({
      name: payload.name,
      attribute_group_id: route.params.id
    })
  }
  await initData()
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

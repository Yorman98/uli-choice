<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router';
import { reactive, ref, Ref } from "vue"
import type { AttributeGroupInterface } from '@/store/types/AttributeInterface'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import UCTable from '@/components/helpers/UCTable.vue'
import UCCreateAttributeDialog from '@/pages/attributes/components/UCCreateAttributeDialog.vue'
import AttributeService from '@/services/AttributeService'

const { t } = useI18n()

const router = useRouter()

const attributeDialog = ref(null)

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]

const attributes: Ref<AttributeGroupInterface[]> = ref([])

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

let attribute: Ref<AttributeGroupInterface> = reactive({
  name: '',
	group_type: '',
})

onMounted(async () => {
  await initData()
})

async function initData() {
  const response = await AttributeService.getAttributeGroup()
  attributes.value = response.data.attributeGroups.data
}

function editItem(payload: AttributeGroupInterface) {
  attribute = Object.assign(payload)
  isEdit.value = true
  attributeDialog.value.openDialog(attribute)
}

function goToGroup(payload: AttributeGroupInterface) {
  router.push({
    name: 'attributesList',
    params: {
      id: payload.id,
    },
  })
}

async function deleteItem(payload: number) {
  await AttributeService.deleteAttributeGroup(payload)
  await initData()
}

function closeDialog() {
  isEdit.value = false
  attribute = Object.assign({
    name: '',
	  group_type: '',
  })
  attributeDialog.value.closeDialog()
}

async function saveAttributeData(payload: AttributeGroupInterface) {
  console.log(payload)
	if (isEdit.value) {
    await AttributeService.updateAttributeGroup({
      id: payload.id,
      name: payload.name,
      group_type: payload.group_type.toLowerCase()
    })
  } else {
		await AttributeService.createAttributeGroup({
			name: payload.name,
			group_type: payload.group_type.toLowerCase()
		})
  }
  await initData()
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
            :hasSubItems="true"
            :items="attributes"
            @editItem="editItem"
            @goToItem="goToGroup"
            @deleteItem="deleteItem"
          />
        </VCardText>
      </VCard>

      <UCCreateAttributeDialog
        ref="attributeDialog"
        :isEdit="isEdit"
        :isGroup="true"
        @closeDialog="closeDialog"
        @saveData="saveAttributeData"
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

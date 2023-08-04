<script setup lang="ts">
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import { useI18n } from 'vue-i18n'
import { ProductInterface } from '@/store/types/ProductInterface'
import { VariantInterface } from '@/store/types/VariantInterface'
import { reactive, ref, Ref } from 'vue'
import { AttributeGroupInterface, AttributeInterface } from "@/store/types/AttributeInterface";


const { t } = useI18n()

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('products.form_product.create_product_title'),
    disabled: true,
  },
]

let product: ProductInterface = reactive({
  id: 0,
  name: '',
  slug: '',
  code: '',
  description: '',
  img: ''
})

const groups: AttributeGroupInterface[] = [
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

const variants: Ref<VariantInterface[]> = ref([])

function addVariant() {
  variants.value.push({
    sku: '',
    price: 0,
    cost: 0,
    stock: 0,
    group: null,
    attributes: []
  })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <UCHeaderPage
        class="mb-4"
        :title="$t('products.form_product.create_product_title')"
        :path="path"
      />

      <VCard
        class="mb-6"
        :title="$t('products.form_product.product_details')"
      >
        <VCardText>
          <VForm>
            <VRow>
              <VCol cols="12">
                <VTextField
                  v-model="product.name"
                  :placeholder="$t('products.form_product.product_name')"
                  :label="$t('products.form_product.product_name')"
                  type="text"
                />
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="product.slug"
                  :placeholder="$t('products.form_product.product_slug')"
                  :label="$t('products.form_product.product_slug')"
                  type="text"
                />
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="product.code"
                  :placeholder="$t('products.form_product.product_code')"
                  :label="$t('products.form_product.product_code')"
                  type="text"
                />
              </VCol>

              <VCol cols="12">
                <VTextarea
                  v-model="product.description"
                  :placeholder="$t('products.form_product.product_description')"
                  :label="$t('products.form_product.product_description')"
                />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>

      <VCard :title="$t('products.form_product.product_variant')">
        <VCardTitle>
          <VBtn
            class="mb-4"
            color="primary"
            @click="addVariant"
          >
            {{ $t('products.form_product.add_variant') }}
          </VBtn>
        </VCardTitle>

        <VCardText
          v-for="(variant, index) in variants"
          class="mb-10 py-0"
          :key="index"
        >
          <p class="text-h6">
            {{ $t('products.form_product.variant') }} {{ index + 1 }}
          </p>
          <VForm>
            <VRow>
              <VCol cols="6">
                <VTextField
                  v-model="variant.sku"
                  :placeholder="$t('products.form_product.product_sku')"
                  :label="$t('products.form_product.product_sku')"
                  type="text"
                />
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="variant.price"
                  :placeholder="$t('products.form_product.product_price')"
                  :label="$t('products.form_product.product_price')"
                  type="number"
                />
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="variant.cost"
                  :placeholder="$t('products.form_product.product_cost')"
                  :label="$t('products.form_product.product_cost')"
                  type="number"
                />
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="variant.stock"
                  :placeholder="$t('products.form_product.product_stock')"
                  :label="$t('products.form_product.product_stock')"
                  type="number"
                />
              </VCol>

              <VCol cols="6">
                <VSelect
                  v-model="variant.group"
                  :items="groups"
                  item-title="name"
                  item-value="id"
                  :placeholder="$t('products.form_product.product_group')"
                  :label="$t('products.form_product.product_group')"
                  type="number"
                />
              </VCol>

              <VCol cols="6">
                <VSelect
                  v-model="variant.attributes"
                  :items="attributes"
                  item-title="name"
                  item-value="id"
                  :placeholder="$t('products.form_product.product_attributes')"
                  :label="$t('products.form_product.product_attributes')"
                  type="number"
                />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped lang="scss">

</style>

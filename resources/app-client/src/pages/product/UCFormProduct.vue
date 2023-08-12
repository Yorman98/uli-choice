<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import type { Ref, UnwrapRef } from 'vue'
import { reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import UCHeaderPage from '@/components/helpers/UCHeaderPage.vue'
import type { ProductInterface } from '@/store/types/ProductInterface'
import type { VariantInterface } from '@/store/types/VariantInterface'
import type { AttributeGroupInterface, AttributeInterface } from '@/store/types/AttributeInterface'
import CategoryService from '@/services/CategoryService'
import ProductService from '@/services/ProductService'
import type { CategoryInterface } from '@/store/types/CategoryInterface'
import imageUrl from '@images/product/product-img.png'

const route = useRoute()

const router = useRouter()

const { t } = useI18n()

const isEdit = !!route.params.id

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: 'adminDashboard',
    },
  },
  {
    title: t('products.products_list'),
    disabled: false,
    to: {
      name: 'product-list',
    },
  },
  {
    title: isEdit ? t('products.form_product.update_product') : t('products.form_product.create_product_title'),
    disabled: true,
  },
]

const product: UnwrapRef<ProductInterface> = reactive({
  name: '',
  slug: '',
  code: '',
  description: '',
  image: '',
} as ProductInterface)

const categories: Ref<CategoryInterface[]> = ref([])

const groups: AttributeGroupInterface[] = [
  {
    id: 1,
    name: 'Talla',
    group_type: 'text',
  },
  {
    id: 2,
    name: 'Color',
    group_type: 'text',
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

const formData = new FormData()

const selectedImage: Ref<any[]> = ref([])

onMounted(async () => {
  const response = await CategoryService.getCategories()

  categories.value = response.data.categories.data
  if (isEdit) {
    const productResponse = await ProductService.getProduct(Number(route.params.id))

    Object.assign(product, productResponse.data.data)
    product.categories = productResponse.data.data.categories.map((category: number) => category.id)
  }
})

function addVariant() {
  variants.value.push({
    sku: '',
    price: 0,
    cost: 0,
    stock: 0,
    attributes: [],
  })
}

const openFileInput = () => {
  const fileInput = document?.querySelector('.img input') as HTMLElement
  if (fileInput)
    fileInput.click()
}

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files)
    product.image = URL.createObjectURL(target.files[0])
}

async function deleteProduct() {
  await ProductService.deleteProduct(Number(route.params.id))
  await router.push({
    name: 'product-list',
  })
}
function saveProductData() {
  formData.append('name', product.name)
  formData.append('slug', product.slug)
  formData.append('code', product.code)
  formData.append('description', product.description)
  formData.append('image', selectedImage.value[0])
  for (const value of product.categories)
    formData.append('categories[]', String(value))

  if (isEdit)
    ProductService.updateProduct(Number(route.params.id), formData)
  else
    ProductService.createProduct(formData)

  router.push({
    name: 'product-list',
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
        <VCardTitle class="d-flex justify-end mb-4">
          <VRow>
            <VCol
              cols="12"
              class="d-flex justify-end"
            >
              <VBtn @click="saveProductData">
                <VIcon
                  class="pr-2"
                  size="35"
                >
                  mdi-content-save-outline
                </VIcon>
                <p class="text-button ma-0">
                  {{ isEdit ? t('products.form_product.update_product') : t('products.form_product.create_product') }}
                </p>
              </VBtn>

              <VBtn
                v-if="isEdit"
                color="error"
                class="ml-4"
                @click="deleteProduct"
              >
                <VIcon
                  class="pr-2"
                  size="35"
                >
                  mdi-trash-can-outline
                </VIcon>
                <p class="text-button ma-0">
                  {{ t('products.form_product.delete_product') }}
                </p>
              </VBtn>
            </VCol>

            <VCol
              cols="12"
              class="d-flex flex-column justify-center align-center mb-4"
            >
              <VImg
                :src="product.image ? product.image : imageUrl"
                class="img-preview"
                max-width="300"
                height="200"
                contain
                @click="openFileInput"
              />

              <VBtn
                class="my-6"
                @click="openFileInput"
              >
                {{ $t('products.form_product.change_photo') }}
              </VBtn>
            </VCol>
          </VRow>
        </VCardTitle>

        <VCardText>
          <VForm>
            <VRow>
              <VCol cols="6">
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

              <VCol cols="12">
                <VTextField
                  v-model="product.code"
                  :placeholder="$t('products.form_product.product_code')"
                  :label="$t('products.form_product.product_code')"
                  type="text"
                />
              </VCol>

              <VCol cols="6">
                <VFileInput
                  v-model="selectedImage"
                  class="img"
                  label="Upload an image"
                  accept="image/*"
                  prepend-icon="mdi-camera"
                  @change="handleFileChange"
                />
              </VCol>

              <VCol cols="12">
                <VSelect
                  v-model="product.categories"
                  :items="categories"
                  item-title="name"
                  item-value="id"
                  :placeholder="$t('products.form_product.product_category')"
                  :label="$t('products.form_product.product_group')"
                  multiple
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
          :key="index"
          class="mb-10 py-0"
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
.v-btn {
  span.v-btn__content p {
    color: #FFFFFF;
  }
}

.img {
  display: none;
}

.img-preview {
  cursor: pointer;
}
</style>

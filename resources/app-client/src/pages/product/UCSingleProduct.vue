<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ProductInterface } from "@/store/types/ProductInterface";
import { ref, Ref } from "vue";
import { useRouter } from "vue-router";
import ProductService from "@/services/ProductService";

const { t } = useI18n();

const router = useRouter();

const selected: any = ref();

const product: Ref<ProductInterface> = ref({} as ProductInterface);


onMounted(async () => {
  const id = Number(router.currentRoute.value.params.id);
  if (id) {
    const response = await ProductService.getProduct(id);
    product.value = response.data?.data;
  }
});

const path: any[] = [
  {
    title: t("global.home"),
    disabled: false,
    to: {
      name: "",
    },
  },
  {
    title: t("products.categories.categories_group_title"),
    disabled: true,
  },
];

const headers: any[] = [
  { title: t("global.headers.id"), align: "start", sortable: false, key: "id" },
  { title: t("global.headers.name"), key: "name" },
  { title: t("global.headers.description"), key: "description" },
  { title: t("global.headers.options"), align: "end", key: "actions", sortable: false },
];
</script>

<template>
  <VRow>
    <VCol cols="5">
      <div class="thumbnail-section pa-3">
        <v-carousel>
          <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
            cover
          ></v-carousel-item>

          <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/hotel.jpg"
            cover
          ></v-carousel-item>

          <v-carousel-item
            src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
            cover
          ></v-carousel-item>
        </v-carousel>
      </div>
    </VCol>

    <VCol cols="7">
      <div class="product-details">
        <div class="product-details__header">
          <h1 class="product-title">{{ product.name }}</h1>
          <span class="product-sku">SKU: {{ product.code }}</span>
        </div>

        <div class="product-details__content mt-3">
          <div class="product-description">
            <h3>Description:</h3>
            <p class="mt-2">{{ product.description }}</p>
          </div>

          <div class="product-pricing">
            <div class="product-price">
              <p>{{ $filters.currencyFormat(product.price) }}</p>
            </div>

            <div class="product-variation">
              <h3>Talla:</h3>

              <!-- Radio Variation -->
              <v-chip-group v-model="selected">
                <v-chip
                  v-for="(variation, index) in product.variants"
                  :value="variation.name"
                  :key="index"
                >
                  {{ variation.name }}
                </v-chip>
              </v-chip-group>

              <!-- Select Variation -->
              <v-select
                class="mt-2"
                label="Talla"
                :items="product.variants"
                :item-title="'name'"
                :item-value="'name'"
                v-model="selected"
              >
              </v-select>
            </div>
          </div>
        </div>

        <div class="product-details__after_content mt-3">
          <div class="product-categories">
            <p>
              Categorías:
              <span v-for="(category, index) in product.categories" :key="index">
                {{ category.name
                }}<span v-if="index !== product.categories.length - 1">, </span
                ><span v-else>.</span>
              </span>
            </p>
          </div>
        </div>
      </div>
    </VCol>
  </VRow>
</template>

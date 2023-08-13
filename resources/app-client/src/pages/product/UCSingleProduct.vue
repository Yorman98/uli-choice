<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ProductInterface } from "@/store/types/ProductInterface";
import { ref, Ref } from "vue";
import { useRouter } from "vue-router";

const { t } = useI18n();

const router = useRouter();

const selected: any = ref();

const product: Ref<ProductInterface> = ref({
  id: 0,
  name: "Top Acuerela Sin Gancho",
  slug: "",
  code: "AB-0001",
  description:
    "Pellentesque auctor eros sed libero fermentum, eget porttitor nisi venenatis. Duis quis tristique dui, quis semper erat. Quisque venenatis elementum nunc, vitae feugiat turpis facilisis nec. Praesent a pharetra tortor, a placerat erat. Nam lobortis lacus fringilla finibus ultrices. Quisque laoreet ipsum a tortor tempus, ac pretium lorem pharetra.",
  img: "https://picsum.photos/200/300",
  price: 24.99,
  categories: [
    {
      id: 1,
      name: "Ropa",
      slug: "ropa",
      description: "Ropa de todo tipo",
      img: "https://picsum.photos/200/300",
      parentId: null,
      children: [],
    },
    {
      id: 2,
      name: "Mujer",
      slug: "mujer",
      description: "Ropa de mujer",
      img: "https://picsum.photos/200/300",
      parentId: 1,
      children: [],
    },
    {
      id: 3,
      name: "Blusas",
      slug: "blusas",
      description: "Blusas de mujer",
      img: "https://picsum.photos/200/300",
      parentId: 2,
      children: [],
    },
  ],
  images: [],
  variants: [
    {
      id: 1,
      name: "Amarillo",
      code: "AM",
      description: "Amarillo",
      img: "https://picsum.photos/200/300",
      price: 24.99,
      productId: 1,
      attributes: [
        {
          id: 1,
          name: "Amarillo",
          attributeGroupId: 1,
        },
      ],
    },
    {
      id: 2,
      name: "Rojo",
      code: "RO",
      description: "Rojo",
      img: "https://picsum.photos/200/300",
      price: 24.99,
      productId: 1,
      attributes: [
        {
          id: 2,
          name: "Rojo",
          attributeGroupId: 1,
        },
      ],
    },
  ],
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

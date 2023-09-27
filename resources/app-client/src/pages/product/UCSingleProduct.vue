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
  <VContainer class="pa-0" :fluid="true">
    <VContainer>
      <VRow class="pa-4">
        <VCol cols="12" xs="12" sm="12" md="5">
          <div class="thumbnail-section pa-3">
            <v-carousel>
              <v-carousel-item :src="product.image" cover></v-carousel-item>
            </v-carousel>
          </div>
        </VCol>

        <VCol cols="12" xs="12" sm="12" md="7">
          <div class="product-details mt-4">
            <div class="product-details__header">
              <h1 class="product-title">{{ product.name }}</h1>
              <span class="product-sku">SKU: {{ product.code }}</span>
            </div>

            <div class="product-details__content mt-3">
              <div class="product-description">
                <h3>Descripción:</h3>
                <p class="mt-2">{{ product.description }}</p>
              </div>

              <div v-if="false" class="product-pricing">
                <div class="product-price">
                  <p>{{ $filters.currencyFormat(0) }}</p>
                </div>

                <div v-if="false" class="product-variation">
                  <h3>Talla:</h3>

                  <v-chip-group v-model="selected">
                    <v-chip
                      v-for="(variation, index) in product.variants"
                      :value="variation.name"
                      :key="index"
                    >
                      {{ variation.name }}
                    </v-chip>
                  </v-chip-group>

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
    </VContainer>

    <VRow class="bg-primary">
      <VCol cols="12"> <VContainer> 
        <h2 class="text-white text-center">
          ¿Te provoca algo? Escoge Uli 
        </h2> 
        </VContainer> </VCol>
    </VRow>


    <VContainer>
      <VRow class="pa-5">
        <VCol cols="12">
          <h2 class="mt-2 text-center"> Suscríbete a Nuestro Boletín </h2>
          <p class="mt-4 text-center"> ¡Suscríbase y sea el primero en conocer todas las ofertas exclusivas, obsequios gratuitos y ofertas únicas en la vida! </p>
        </VCol>

      </VRow>
    </VContainer>

  </VContainer>
</template>

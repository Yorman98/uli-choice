<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ProductInterface } from "@/store/types/ProductInterface";
import { ref, Ref } from "vue";
import { useRouter } from "vue-router";
import ProductService from "@/services/ProductService";
import { useUserStore } from '@/store/user'
import FloatingCart from '@/components/global/UCFloatingCart.vue'


const userStore = useUserStore()

const { t } = useI18n();

const router = useRouter();

const selected: { [key: string]: any } = ref({});
const processOrder = ref(false)

const product: Ref<ProductInterface> = ref({} as ProductInterface);

/*
 * Cart Info
 */
const cartInfo: UnwrapNestedRefs<ProductCartRequestInterface> = reactive({
  user_id: null,
  product_id: null,
  variation_id: null,
  quantity: 0,
} as ProductCartRequestInterface);


async function saveProductsCart() {
  await ProductService.addProductCart(cartInfo).then (resp => {
     ProductService.getProductsCart(cartInfo.user_id);
  });
  cartInfo.quantity = 0;
}

onMounted(async () => {
  const id = Number(router.currentRoute.value.params.id);
  if (id) {
    const response = await ProductService.getProduct(id);
    product.value = response.data?.data;
    cartInfo.product_id = product.value.id;
    cartInfo.user_id =  userStore.getUserInfo.id;

  }
});

const attrData =
  computed((variations: any[] = product?.value?.variations ?? []) => {
    let data: { [key: string | number]: any } = { groups: null, combinations: null };

    /*
     * Groups
     */
    let groups: { [key: string | number]: any[] } = {};
    for (let variation of variations) {
      for (let attribute of variation.attributes) {
        let groupId = attribute.group.name ?? attribute.attribute_group_id;
        if (!groups[groupId]) {
          groups[groupId] = [];
        }
        // Evitar duplicados
        if (!groups[groupId].some((a) => a.id === attribute.id)) {
          groups[groupId].push(attribute);
        }
      }
    }

    /*
     * Combinations
     */
    let groupIds = Object.keys(groups);
    let possibleCombinations: any[] = [];

    // Función auxiliar recursiva
    function helper(index: number, current: any[]) {
      if (index === groupIds.length) {
        // Se ha recorrido todos los grupos, se añade la combinación actual al resultado
        possibleCombinations.push(current.slice());
        return;
      }

      // Se obtiene el grupo actual y se itera sobre sus atributos
      let groupId = groupIds[index];

      let group = groups[groupId] ?? [];
      for (let attribute of group) {
        // Se añade el atributo a la combinación actual y se llama a la función con el siguiente grupo
        current.push(attribute);
        helper(index + 1, current);
        // Se quita el atributo de la combinación actual para probar con otro
        current.pop();
      }
    }

    // Se llama a la función auxiliar con el primer grupo y una combinación vacía
    helper(0, []);

    data.groups = groups;
    data.combinations = possibleCombinations;

    return data;
  }) ?? [];

const filterCombinations = (combinations: any[], selections: any[]) => {
  if (
    Object.keys(selections)?.length === 0 ||
    combinations.length != Object.keys(selections).length
  ) {
    return [];
  }

  return combinations?.filter((combination) => {
    for (const groupName in selections) {
      const selectedValue = selections[groupName];
      const found = combination.find((attribute: any) => {
        return attribute.group.name === groupName && attribute.name === selectedValue;
      });
      if (!found) {
        return false; // No se encontró la selección actual en esta combinación
      }
    }
    return true; // Todas las selecciones coinciden con esta combinación
  });
};

function findMatchingVariation(variations: any[], combinations: any[][]): any | string {
  if (combinations.length === 0) {
    return false;
  }

  const matchingVariation = variations?.find((variation) => {
    const attributes = variation.attributes;

    const matchedAttributes = combinations.flatMap((combination) =>
      attributes.filter((attribute) =>
        combination.some(
          (selectedAttribute) =>
            attribute.group.name === selectedAttribute.group.name &&
            attribute.name === selectedAttribute.name
        )
      )
    );

    return matchedAttributes.length === combinations.flat().length;
  });

  if (matchingVariation) {
    cartInfo.variation_id = matchingVariation.id;
    return matchingVariation;
  } else {
    Object.assign(cartInfo, {
      product_id: null,
      variation_id: null,
      quantity: 0,
    });
    return false;
  }
}

// Computed property match
const match = computed(() => {
  return findMatchingVariation(
    product.value.variations,
    filterCombinations(attrData.value.combinations, selected.value)
  );
});

function getAttrsNames(attrs: Array<{ name: string }>): Array<string> {
  return attrs?.map((item) => item.name) || [];
}

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
              <v-carousel-item
                :src="match.image || product.image"
                cover
              ></v-carousel-item>
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

              <div class="product-pricing">
                <div v-if="match && match.stock > 0" class="product-price">
                  <p style="color: #000; font-weigth: 500" class="mb-1">
                    {{ $filters.currencyFormat(match.price) }}
                  </p>

                  <div class="product-add-to-cart mb-2 mt-5">
                    <v-row align="center">
                      <v-col cols="6" xs="6" sm="6" md="4">
                        <v-text-field
                          v-model="cartInfo.quantity"
                          label="Cantidad"
                          type="number"
                          min="1"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" xs="6" sm="6" md="4">
                        <v-btn @click="saveProductsCart" color="primary">Agregar al carro</v-btn>
                      </v-col>
                    </v-row>
                  </div>
                  <span class="mb-5"
                    >Productos disponibles en almacen: {{ match.stock }}
                  </span>
                </div>
                <div class="mb-5" v-else>Articulo no disponible</div>

                <div
                  v-if="Object.keys(attrData?.groups)?.length > 0"
                  class="product-variation mt-3"
                >
                  <div v-for="(attr, name) in attrData?.groups" :key="name">
                    <h3>{{ name }}:</h3>

                    <v-chip-group v-model="selected[name]">
                      <v-chip
                        v-for="(item, index) in getAttrsNames(attr)"
                        :value="item"
                        :key="index"
                      >
                        {{ item }}
                      </v-chip>
                    </v-chip-group>

                    <!-- SELECT <v-select
                      class="mt-2"
                      label="Talla"
                      :items="getAttrsNames(attr)"
                      :item-title="'name'"
                      :item-value="'name'"
                      v-model="selected"
                    >
                    </v-select> -->
                  </div>
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
      <VCol cols="12">
        <VContainer>
          <h2 class="text-white text-center">¿Te provoca algo? Escoge Uli</h2>
        </VContainer>
      </VCol>
    </VRow>

    <VContainer>
      <VRow class="pa-5">
        <VCol cols="12">
          <h2 class="mt-2 text-center">Suscríbete a Nuestro Boletín</h2>
          <p class="mt-4 text-center">
            ¡Suscríbase y sea el primero en conocer todas las ofertas exclusivas,
            obsequios gratuitos y ofertas únicas en la vida!
          </p>
        </VCol>
      </VRow>
    </VContainer>
  </VContainer>
</template>

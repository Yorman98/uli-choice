<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ProductInterface } from "@/store/types/ProductInterface";
import { ref, Ref } from "vue";
import { useRouter } from "vue-router";
import ProductService from "@/services/ProductService";

const { t } = useI18n();

const router = useRouter();

const selected: { [key: string]: any } = ref({});

const product: Ref<ProductInterface> = ref({} as ProductInterface);

onMounted(async () => {
  const id = Number(router.currentRoute.value.params.id);
  if (id) {
    const response = await ProductService.getProduct(id);
    product.value = response.data?.data;
  }
});

const attrData = computed((variations: any[] = product?.value?.variations ?? []) => {
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

  if (Object.keys(selections).length === 0 ||  combinations.length != Object.keys(selections).length)  {
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


function findMatchingVariation(variations: any[], combinations: any[]): any | string {
  
  if (Object.keys(combinations).length === 0) {
    return [];
  }
  
  const matchingVariation = variations?.find((variation) => {
    
    const attributes = variation.attributes;
    console.log(combinations)
    return combinations?.every((combination) => {
      
      return combination.some((selectedAttribute) => {
        const matchingAttribute = attributes.find((attribute) => {
          return (
            attribute.group.name === selectedAttribute.group.name &&
            attribute.name === selectedAttribute.name
          );
        });
        return matchingAttribute !== undefined;
      });
    });
  });

  if (matchingVariation) {
    return matchingVariation;
  } else {
    return "No se encontró una variación que coincida con las combinaciones proporcionadas.";
  }
}

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

    <pre>
      {{ filterCombinations(attrData.combinations, selected) }}
    </pre>

    <pre>
      {{  findMatchingVariation(product.variations,  filterCombinations(attrData.combinations, selected)) }}
    </pre>

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

              <pre>
                {{ selected }}
              </pre>

              <div class="product-pricing">
                <div class="product-price">
                  <p>{{ $filters.currencyFormat(0) }}</p>
                </div>

                <div
                  v-if="Object.keys(attrData?.groups)?.length > 0"
                  class="product-variation"
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

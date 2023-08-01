<script setup lang="ts">
import { VDataTable } from 'vuetify/labs/VDataTable'

defineProps({
  headers: {
    type: Array,
    default: () => []
  },
  items: {
    type: Array,
    default: () => []
  },
  noDataText: {
    type: String,
    default: 'No data available'
  }
});

const emit = defineEmits(['editItem', 'deleteItem'])

function editItem (item: Object) {
  emit('editItem', item.selectable);
}

function deleteItem (item: Object) {
  emit('deleteItem', item.selectable.id);
}
</script>

<template>
  <VDataTable
    :headers="headers as any"
    :items="items"
    class="uc-table rounded-lg elevation-1"
  >
    <template v-slot:item.actions="{ item }">
      <v-menu location="left">
        <template v-slot:activator="{ props }">
          <v-icon v-bind="props">
            mdi-dots-vertical
          </v-icon>
        </template>

        <v-list>
          <v-list-item  @click="editItem(item)">
            <template v-slot:prepend>
              <v-icon icon="mdi-pencil-outline"/>
            </template>

            <v-list-item-title v-text="$t('global.edit')"></v-list-item-title>
          </v-list-item>

          <v-list-item @click="deleteItem(item)">
            <template v-slot:prepend>
              <v-icon icon="mdi-delete-outline"/>
            </template>

            <v-list-item-title v-text="$t('global.delete')"></v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

    </template>
    <template v-slot:no-data>
     <p>
       {{ noDataText }}
     </p>
    </template>
  </VDataTable>
</template>

<style lang="scss">
.v-data-table th {
  background: #696CFF !important;
  color: white !important;
}

.v-list-item {
  cursor: pointer !important;
}
</style>

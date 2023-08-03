<script setup lang="ts">
import { VDataTable } from 'vuetify/labs/VDataTable'

defineProps({
  headers: {
    type: Array,
    default: () => [],
  },
  items: {
    type: Array,
    default: () => [],
  },
  noDataText: {
    type: String,
    default: 'No data available',
  },
  hasSubItems: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['editItem', 'deleteItem', 'goToItem'])

function editItem(item: Object) {
  emit('editItem', item.selectable)
}

function goToItem(item: Object) {
  emit('goToItem', item.selectable)
}

function deleteItem(item: Object) {
  emit('deleteItem', item.selectable.id)
}
</script>

<template>
  <VDataTable
    :headers="headers as any"
    :items="items"
    :no-data-text="noDataText"
    class="uc-table rounded-lg elevation-1"
  >
    <template #item.actions="{ item }">
      <VMenu location="left">
        <template #activator="{ props }">
          <VIcon v-bind="props">
            mdi-dots-vertical
          </VIcon>
        </template>

        <VList>
          <VListItem
            v-if="hasSubItems"
            @click="goToItem(item)"
          >
            <template #prepend>
              <VIcon icon="mdi-eye-outline" />
            </template>

            <VListItemTitle v-text="$t('global.see')" />
          </VListItem>

          <VListItem @click="editItem(item)">
            <template #prepend>
              <VIcon icon="mdi-pencil-outline" />
            </template>

            <VListItemTitle v-text="$t('global.edit')" />
          </VListItem>

          <VListItem @click="deleteItem(item)">
            <template #prepend>
              <VIcon icon="mdi-delete-outline" />
            </template>

            <VListItemTitle v-text="$t('global.delete')" />
          </VListItem>
        </VList>
      </VMenu>
    </template>
    <template #no-data>
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

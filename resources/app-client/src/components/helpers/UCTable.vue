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
  },
  onlyEdit: {
    type: Boolean,
    default: false,
  },
  editAndGoto: {
    type: Boolean,
    default: false,
  },
  onlyGoTo: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['editItem', 'deleteItem', 'goToItem'])

function editItem(item: NonNullable<unknown>) {
  emit('editItem', item.selectable)
}

function goToItem(item: NonNullable<unknown>) {
  emit('goToItem', item.selectable)
}

function deleteItem(item: NonNullable<unknown>) {
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
            v-if="hasSubItems || editAndGoto || onlyGoTo"
            @click="goToItem(item)"
          >
            <template #prepend>
              <VIcon icon="mdi-eye-outline" />
            </template>

            <VListItemTitle v-text="$t('global.see')" />
          </VListItem>

          <VListItem
            v-if="hasSubItems || editAndGoto || onlyEdit"
            @click="editItem(item)"
          >
            <template #prepend>
              <VIcon icon="mdi-pencil-outline" />
            </template>

            <VListItemTitle v-text="$t('global.edit')" />
          </VListItem>

          <VListItem
            v-if="!editAndGoto && !onlyGoTo"
            @click="deleteItem(item)"
          >
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

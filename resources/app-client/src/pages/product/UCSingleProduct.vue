<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { ProductInterface } from '@/store/types/ProductInterface';
import { ref, Ref } from 'vue'
import { useRouter } from 'vue-router'

const { t } = useI18n()

const router = useRouter()

const path: any[] = [
  {
    title: t('global.home'),
    disabled: false,
    to: {
      name: '',
    },
  },
  {
    title: t('products.categories.categories_group_title'),
    disabled: true,
  },
]

const headers: any[] = [
  { title: t('global.headers.id'), align: 'start', sortable: false, key: 'id' },
  { title: t('global.headers.name'), key: 'name' },
  { title: t('global.headers.description'), key: 'description' },
  { title: t('global.headers.options'), align: 'end', key: 'actions', sortable: false },
]




</script>
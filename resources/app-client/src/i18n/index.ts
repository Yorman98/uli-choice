import { createI18n } from "vue-i18n";
import messages from '@/i18n/es-ES/index.json';

export default createI18n({
  locale: 'es-ES', // set locale
  fallbackLocale: 'es', // set fallback locale
  messages: {
    'es-ES': messages
  }
})


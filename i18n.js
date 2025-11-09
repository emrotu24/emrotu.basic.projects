import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

import translationEN from './src/Data/en_language/translation-EN.json';
import translationIT from './src/Data/it_language/translation-IT.json';
import translationES from './src/Data/es_language/translation-ES.json';

const resources = {
  en: { translation: translationEN },
  it: { translation: translationIT },
  es: { translation: translationES },
};

i18n
  .use(LanguageDetector) // rileva automaticamente la lingua del browser
  .use(initReactI18next) // collega i18next a React
  .init({
    resources,
    fallbackLng: 'es', // lingua di default se non viene rilevata o supportata
    supportedLngs: ['en', 'it', 'es'], // opzionale ma utile per limitare le lingue supportate
    interpolation: {
      escapeValue: false, // React già protegge da XSS
    },
    detection: {
      order: ['querystring', 'cookie', 'localStorage', 'navigator', 'htmlTag'],
      caches: ['localStorage', 'cookie'],
    },
    react: {
      useSuspense: false, // utile se non usi Suspense
    },
  });

export default i18n;


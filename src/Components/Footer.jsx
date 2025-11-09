import { useTranslation } from 'react-i18next';

export default function Footer() {

  const { t } = useTranslation();

  return(
    <footer className="footer text-xs md:text-base flex flex-col items-center mt-20 mb-4 mx-2">
      <p>&copy; 2025 Emanuele Rotundi Workhouse. {t('footer.rights')}</p>
    </footer>
  )
}
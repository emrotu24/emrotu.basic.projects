import { useTranslation } from 'react-i18next';

export default function Footer() {

  const { t } = useTranslation();

  return(
    <footer className="footer text-center text-xs md:text-base flex flex-col items-center my-10 mx-2">
      <p>&copy; 2025 Emanuele Rotundi Workhouse. {t('footer.rights')}</p>
    </footer>
  )
}
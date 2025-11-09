import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { GlobeAltIcon } from '@heroicons/react/24/solid';
import { Tooltip } from 'react-tooltip';

const LanguageSwitcher = () => {
  const { i18n } = useTranslation();
  const [open, setOpen] = useState(false);
  const [currentLang, setCurrentLang] = useState(i18n.language);
  const { t } = useTranslation();

  useEffect(() => {
    const savedLang = localStorage.getItem('lang');
    if (savedLang) {
      i18n.changeLanguage(savedLang);
      setCurrentLang(savedLang);
    }
  }, [i18n]);

  const changeLanguage = (lng) => {
    i18n.changeLanguage(lng);
    setCurrentLang(lng);
    localStorage.setItem('lang', lng);
    setOpen(false);
  };

  return (
      <div className="flex flex-col md:flex md:flex-row gap-2 justify-center">
        <button onClick={() => setOpen(!open)} className="flex items-center  gap-1 px-2 py-1 rounded hover:bg-purple-700 transition"
          data-tooltip-id="my-tooltip" data-tooltip-content={!open ? t('tooltip.language') : undefined}>
          <Tooltip id="my-tooltip" className='!text-sm'/>
          <GlobeAltIcon className="h-3 xl:h-5 text-white" />
          <span className="uppercase text-xs xl:text-base font-semibold text-white">{currentLang}</span>
        </button>
  
          {open &&(
            <div className='flex flex-col md:flex md:flex-row gap-2'>
              <button onClick={() => changeLanguage('es')} className="text-xs xl:text-base px-2 bg-black hover:bg-gray-800 text-yellow-400 rounded">ES</button>
              <button onClick={() => changeLanguage('en')} className="text-xs xl:text-base px-2 bg-gray-500 hover:bg-gray-400 text-white rounded">EN</button>
              <button onClick={() => changeLanguage('it')} className="text-xs xl:text-base px-2 bg-neutral-200 hover:bg-neutral-50 text-black rounded">IT</button>
            </div>
          )}
      </div>
  );
};

export default LanguageSwitcher;

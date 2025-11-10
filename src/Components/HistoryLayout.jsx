import PescaraIntro from '../medias/PescaraIntro.jpg'
import PescaraView3 from '../medias/PescaraView3.jpg'
import PescaraPonteDelMare from '../medias/PescaraPonteDelMare.jpg'
import PescaraFromAbove from '../medias/PescaraFromAbove.jpg'
import PescaraFromMountain from '../medias/PescaraFromMountain.jpg'
import Budismo from '../medias/Budismo.jpg'
import basketball1 from '../medias/basketball1.jpg'
import JapanCultureBonsai from '../medias/JapanCultureBonsai.jpg'
import BioImage from '../medias/Myself.jpg'

import { useTranslation } from 'react-i18next';

import { FaChevronLeft, FaChevronRight } from 'react-icons/fa';

import { useState, useEffect, useRef } from 'react';

import { Tooltip } from 'react-tooltip';

export default function HistoryLayout() {

  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  const { t } = useTranslation();

  const images = [
    {
      src: PescaraPonteDelMare,
      alt: 'Ponte del Mare',
      description: t("history.images.ponteDelMare")
    },
    {
      src: PescaraFromAbove,
      alt: 'Vista dall’alto',
      description: t("history.images.fromAbove")
    },
    {
      src: PescaraFromMountain,
      alt: 'Vista dalla montagna',
      description: t("history.images.fromMountain")
    },
  ];

  const [currentIndex, setCurrentIndex] = useState(0);
  const timerRef = useRef(null);

  // Funzione per avviare il timer
  const startTimer = () => {
    timerRef.current = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % images.length);
    }, 5000);
  };

  // Funzione per fermare il timer
  const stopTimer = () => {
    if (timerRef.current) {
      clearInterval(timerRef.current);
      timerRef.current = null;
    }
  };

  // Avvio del timer all'inizio
  useEffect(() => {
    startTimer();
    return () => stopTimer(); // Cleanup
  }, []);

  // Quando l'utente clicca manualmente, resetta il timer
  const handleManualChange = (newIndex) => {
    stopTimer(); // Ferma il timer
    setCurrentIndex(newIndex); // Cambia immagine
    startTimer(); // Riavvia il timer
  };

  const prevImage = () => {
    handleManualChange(currentIndex === 0 ? images.length - 1 : currentIndex - 1);
  };

  const nextImage = () => {
    handleManualChange((currentIndex + 1) % images.length);
  };

  const [isLargeScreen, setIsLargeScreen] = useState(window.innerWidth >= 1024);

  useEffect(() => {
      const handleResize = () => {
      setIsLargeScreen(window.innerWidth >= 1024);
      };
      window.addEventListener('resize', handleResize);
      return () => window.removeEventListener('resize', handleResize);
  }, []);

  const { src, alt, description } = images[currentIndex];

  return (
    <main className="mt-12 xl:mt-24">

      {/* INTRO */}
      <section className="relative grid grid-cols-1 md:grid-cols-2 h-auto overflow-hidden">
        <img className="w-full h-full object-cover" src={PescaraIntro} alt="PescaraIntro" />
        <div className="absolute z-10 top-12 sm:top-28 md:top-6 lg:top-20 left-5 sm:left-10 flex flex-col gap-y-5 md:gap-y-8 xl:gap-y-14 italic text-sm lg:text-lg xl:text-2xl text-shadow-white font-medium leading-relaxed animate-in slide-in-from-left duration-1000">
          <p>{t("history.intro.subtitle1")}</p>
          <p>{t("history.intro.subtitle2")}</p>
          <p>{t("history.intro.subtitle3")}</p>
        </div>

        <p className="absolute z-10 backdrop-blur-sm left-1/4 md:left-1/3 top-1/2 text-2xl sm:text-4xl xl:text-6xl text-white font-extrabold text-shadow-black animate-in slide-in-from-right duration-200">
          {t("history.intro.title")}
        </p>
        <img className="w-full h-full object-cover" src={PescaraView3} alt="PescaraView3" />
      </section>

      {/* BIO */}
      <section className="flex flex-col md:flex-row h-auto">
        <img className="md:w-2/5 object-cover " src={BioImage} alt="Myself" />
        <div className="md:w-3/5 flex flex-col justify-center bg-purple-700 py-6 px-4">
          <h2 className="text-center text-2xl md:text-3xl pb-8 font-bold text-white">{t("history.bio.title")}</h2>
          <p className="text-base md:text-xl text-center leading-loose text-white">
            {t("history.bio.text")}
          </p>
        </div>
      </section>

      {/* SEZIONI */}
      <h1 className="py-4 text-2xl md:text-4xl xl:text-5xl font-bold text-center bg-yellow-400">
        {t("history.sections.title")}
      </h1>

      <section className="grid grid-cols-1 lg:grid-cols-2 mb-4">
        {/* Bilanciamento */}
        <img src={Budismo} alt="Budismo" className="w-full h-auto object-cover" />
        <div className="bg-yellow-400 px-4 py-6 flex flex-col gap-6 justify-center items-center">
          <h2 className="text-center font-bold text-xl md:text-3xl pb-2 lg:pb-8">{t("history.sections.balance.title")}</h2>
          <p className="text-base md:text-xl px-2 sm:px-4 leading-loose">{t("history.sections.balance.text")}</p>
        </div>

        {/* Cultura */}
        <img src={JapanCultureBonsai} alt="JapanCultureBonsai" className="w-full h-auto object-cover" />
        <div className="bg-yellow-400 px-4 py-6 flex flex-col gap-6 justify-center items-center">
          <h2 className="text-center font-bold text-xl md:text-3xl pb-2 lg:pb-8">{t("history.sections.culture.title")}</h2>
          <p className="text-base sm:text-xl px-2 sm:px-4 leading-loose">{t("history.sections.culture.text")}</p>
        </div>

        {/* Sport */}
        <img src={basketball1} alt="basketball1" className="w-full h-auto object-cover" />
        <div className="bg-yellow-400 px-4 py-6 flex flex-col gap-6 justify-center items-center">
          <h2 className="text-center font-bold text-xl md:text-3xl pb-2 lg:pb-8">{t("history.sections.sport.title")}</h2>
          <p className="text-base sm:text-xl px-2 sm:px-4 leading-loose">{t("history.sections.sport.text")}</p>
        </div>
      </section>

      {/* GALLERIA */}
      <section className="flex flex-col items-center">
        <p className="my-8 text-2xl md:text-4xl xl:text-5xl font-bold text-center">
          {t("history.gallery.title")}
        </p>

        <div className="relative w-11/12 aspect-video overflow-hidden rounded-lg shadow-lg">
          <img src={src} alt={alt} loading="lazy" className="w-full h-full object-cover" />
          <button
            onClick={prevImage}
            className="group absolute top-1/2 left-1 px-2 py-2 flex items-center justify-center lg:hover:bg-gray-500 rounded-full hover:bg-opacity-30"
            data-tooltip-id="prevImg-tooltip"
            data-tooltip-content={!open && isLargeScreen ? t('tooltip.prevImg') : undefined}
          >
            <Tooltip id="prevImg-tooltip" className="!text-sm" />
            <FaChevronLeft className="text-sm md:text-xl lg:group-hover:text-2xl" />
          </button>
          <button
            onClick={nextImage}
            className="group absolute top-1/2 right-1 px-2 py-2 flex items-center justify-center lg:hover:bg-gray-500 rounded-full hover:bg-opacity-30"
            data-tooltip-id="nextImg-tooltip"
            data-tooltip-content={!open && isLargeScreen ? t('tooltip.nextImg') : undefined}
          >
            <Tooltip id="nextImg-tooltip" className="!text-sm" />
            <FaChevronRight className="text-sm md:text-xl lg:group-hover:text-2xl" />
          </button>
        </div>

        {/* Descrizione */}
        <p className="my-6 mx-2 text-base sm:text-xl text-center">{description}</p>

        {/* Indicatori */}
        <div className="flex gap-2">
          {images.map((_, i) => (
            <button
              key={i}
              onClick={() => handleManualChange(i)}
              className={`w-3 h-3 md:w-5 md:h-5 rounded-full ${i === currentIndex ? 'bg-black' : 'bg-gray-300'}`}
            />
          ))}
        </div>
      </section>
    </main>
  ); 
}
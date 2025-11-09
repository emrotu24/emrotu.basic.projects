import IntroductionWallpaper from '../medias/IntroductionWallpaper.jpg';
import MyDevKnowledgeBg from '../medias/MyDevKnowledgeBg.mp4'
import LastProjectsBg from '../medias/LastProjectsBg.mp4'
import WorkingProgressBg from '../medias/WorkingProgressBg.mp4'
import KnowmeBetterBg from '../medias/KnowmeBetterBg.mp4'

import { useTranslation } from "react-i18next";

import { useNavigate } from 'react-router-dom';

export default function Overview() {
    const { t } = useTranslation();

    const navigate = useNavigate();

    return(
        <main className="mt-12 lg:mt-24">

{/* INTRODUCTION SECTION */}
            <section className="introduction-box relative overflow-hidden group bg-cover bg-no-repeat bg-center py-12 lg:py-32"
                    style={{ backgroundImage: `url(${IntroductionWallpaper})`}}>
                <div className='flex flex-col items-center justify-center text-center gap-5'>
                  <h1 className="z-10 text-xl md:text-2xl lg:text-5xl text-outline-white font-extrabold text-purple-600 mb-5">{t("home.intro.title")}</h1>
                  <p className="z-10 text-base md:text-lg lg:text-2xl text-outline-black text-white font-extrabold mx-2 md:mx-8 lg:leading-relaxed">{t("home.intro.text")}</p>
                </div>
            </section>

            <section className="overview-section-box w-full grid grid-cols-1 lg:grid-cols-2 justify-items-center gap-5 mt-5 px-5">

{/* PROJECTS SECTION */}
                <div className="last-proyects-box flex flex-col justify-center text-center min-h-52 lg:min-h-64 w-full relative group hover:scale-105 overflow-hidden rounded-lg shadow-lg cursor-pointer"
                    onClick={() => navigate('/projects?filtro=completed')}
                    onMouseEnter={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.play();
                    }}
                    onMouseLeave={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.pause();
                    }}>
                    <video src={LastProjectsBg}
                        className="absolute top-0 left-0 w-full h-full object-cover z-0"
                        muted
                        loop
                        playsInline>
                    </video>
                    <div className="absolute top-0 left-0 w-full h-full bg-black/20 z-10" />
                    <div className='relative py-2 lg:py-12 z-20'>
                        <h2 className="group-hover:animate-bounce mb-8 font-extrabold text-outline-white text-purple-600 text-2xl lg:text-4xl">{t("home.projects.title")}</h2>
                        <p className="text-base lg:text-2xl text-outline-black text-white font-extrabold mx-8 lg:leading-relaxed">{t("home.projects.text")}</p>  
                    </div>
                </div>

{/* WORKING PROGRESS SECTION */}
                <div className="working-progress-box  flex flex-col items-center justify-center text-center min-h-52 lg:min-h-64 w-full relative group hover:scale-105 overflow-hidden rounded-lg shadow-lg cursor-pointer"
                    onClick={() => navigate('/projects?filtro=working')}
                    onMouseEnter={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.play();
                    }}
                    onMouseLeave={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.pause();
                    }}>
                    <video src={WorkingProgressBg}
                        className="absolute top-0 left-0 w-full h-full object-cover z-0"
                        muted
                        loop
                        playsInline>
                    </video>
                    <div className="absolute top-0 left-0 w-full h-full bg-black/20 z-10" />
                    <div className='z-20 py-2 lg:py-12 gap-5'>
                        <h2 className="group-hover:animate-bounce font-extrabold mb-8 text-outline-white text-purple-600 text-2xl lg:text-4xl">{t("home.working.title")}</h2>
                        <p className="text-base lg:text-2xl text-outline-black text-white font-extrabold mx-8 lg:leading-relaxed">{t("home.working.text")}</p>
                    </div>
                </div>
                
{/* KNOW ME BETTER SECTION */}
                <div className="know-me-better-box flex flex-col items-center justify-center text-center min-h-52 lg:col-span-2 lg:min-h-64 w-full relative group hover:scale-105 overflow-hidden rounded-lg shadow-lg cursor-pointer"
                    onClick={() => navigate('/history')}
                    onMouseEnter={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.play();
                    }}
                    onMouseLeave={(e) => {
                        const video = e.currentTarget.querySelector('video');
                        video?.pause();
                    }}>
                    <video src={KnowmeBetterBg}
                        className="absolute top-0 left-0 w-full h-full object-cover z-0"
                        muted
                        loop
                        playsInline>
                    </video>
                    <div className="absolute top-0 left-0 w-full h-full bg-black/20 z-10" />
                    <div className='z-20 py-2 lg:py-12 gap-5'>
                        <h2 className="group-hover:animate-bounce font-extrabold mb-8 text-outline-white text-purple-600 text-2xl lg:text-4xl">{t("home.knowme.title")}</h2>
                        <p className="text-base lg:text-2xl text-outline-black text-white font-extrabold mx-8 lg:leading-relaxed">{t("home.knowme.text")}</p>
                    </div>
                </div>
            </section>
        </main>
    )
}
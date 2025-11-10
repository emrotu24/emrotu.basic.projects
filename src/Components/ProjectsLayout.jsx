import projects_data from '../Data/projects_data.json';
import { ChevronDownIcon } from "@heroicons/react/24/solid";
import { useState, useEffect, useRef } from 'react';
import { useSearchParams } from 'react-router-dom';
import { useTranslation } from "react-i18next";
import { Tooltip } from 'react-tooltip';
import { Link } from 'react-router-dom';

export default function ProjectsLayout() {
    const { t } = useTranslation();
    const [open, setOpen] = useState(false);
    const filterButtonRef = useRef(null);
    const filterMenuRef = useRef(null);

    const [searchParams, setSearchParams] = useSearchParams();
    const [filteredProjects, setFilteredProjects] = useState(projects_data);
    const [filterStatus, setfilterStatus] = useState("");
    const [filterTitle, setfilterTitle] = useState("");

    const statusMap = {
        completed: { label: t('projects.FliterStatus.Completed'), value: 'Completed' },
        working: { label: t('projects.FliterStatus.In progress'), value: 'In progress' },
        ideas: { label: t('projects.FliterStatus.Future ideas'), value: 'Future ideas' },
        all: { label: t('projects.FliterStatus.all'), value: null }
    };

    const titleMap = {
        completed: t('projects.FilterTitle.Completed'),
        working: t('projects.FilterTitle.In progress'),
        ideas: t('projects.FilterTitle.Future ideas'),
        all: t('projects.FilterTitle.all')
    };

    function HandleFilter(key) {
        setSearchParams({ filtro: key });
        const statusEntry = statusMap[key];
        const translatedTitle = titleMap[key];
        setfilterStatus(statusEntry.label);
        setfilterTitle(translatedTitle);

        if (statusEntry.value) {
            const filtered = projects_data.filter(p => p.status === statusEntry.value);
            setFilteredProjects(filtered);
        } else {
            setFilteredProjects(projects_data);
        }
    }

    useEffect(() => {
        window.scrollTo(0, 0);
    }, []);

    useEffect(() => {
        const filtroQuery = searchParams.get('filtro');
        HandleFilter(filtroQuery || 'all');
    }, [searchParams, t]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (
                filterMenuRef.current &&
                !filterMenuRef.current.contains(event.target) &&
                filterButtonRef.current &&
                !filterButtonRef.current.contains(event.target)
            ) {
                setOpen(false);
            }
        }

        document.addEventListener('click', handleClickOutside);
        return () => {
            document.removeEventListener('click', handleClickOutside);
        };
    }, []);

    const [isLargeScreen, setIsLargeScreen] = useState(window.innerWidth >= 1024);

    useEffect(() => {
        const handleResize = () => {
        setIsLargeScreen(window.innerWidth >= 1024);
        };
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    return (

        <section>
            {/* Filtro */}
            <div className="fixed bg-white w-full top-14 lg:top-24 flex sm:flex-row items-center justify-center shadow-md py-2 gap-6 sm:gap-40 px-4 z-10 text-center">
                <div className="relative text-sm md:text-lg lg:text-3xl font-medium drop-shadow-md py-2">
                    {filterTitle}
                </div>

                <div
                    className="relative bg-yellow-300 flex gap-2 items-center text-xs md:text-base justify-center py-1 px-1 rounded-sm shadow-md cursor-pointer hover:bg-yellow-200 min-w-[120px] max-w-[160px]"
                    data-tooltip-id="filter-tooltip"
                    data-tooltip-content={!open && isLargeScreen ? t('tooltip.filter') : undefined}
                    onClick={() => setOpen(prev => !prev)}
                    ref={filterButtonRef}
                >
                    <Tooltip id="filter-tooltip" place="bottom" className="!text-sm" />
                    <p className="filter-text font-semibold">{filterStatus}</p>
                    <ChevronDownIcon
                        className={`h-3 w-3 font-semibold text-black transform transition-transform duration-300 ${open ? 'rotate-180' : 'rotate-0'}`}
                    />

                    {open && (
                        <ul className="absolute py-2 w-full left-0 top-8 md:top-10 bg-yellow-300 shadow-lg" ref={filterMenuRef}>
                            <li className="hover:bg-yellow-200 text-center px-2 py-1 cursor-pointer" onClick={() => HandleFilter('all')}>
                                {t('projects.FliterStatus.all')}
                            </li>
                            <li className="hover:bg-yellow-200 text-center px-2 py-1 cursor-pointer" onClick={() => HandleFilter('completed')}>
                                {t('projects.FliterStatus.Completed')}
                            </li>
                            <li className="hover:bg-yellow-200 text-center px-2 py-1 cursor-pointer" onClick={() => HandleFilter('working')}>
                                {t('projects.FliterStatus.In progress')}
                            </li>
                            <li className="hover:bg-yellow-200 text-center px-2 py-1 cursor-pointer" onClick={() => HandleFilter('ideas')}>
                                {t('projects.FliterStatus.Future ideas')}
                            </li>
                        </ul>
                    )}
                </div>
            </div>

            {/* Progetti */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-32 lg:mt-48 mb-5 mx-4 sm:mx-10">
                {filteredProjects.map((projects, index) => {
                    const isClickable = projects.link && projects.link.trim() !== '';
                    const isExternal = isClickable && projects.link.startsWith('http');
                    const isStaticLocal = isClickable && projects.link.startsWith('projects/');
                    const href = isExternal
                    ? projects.link
                    : isStaticLocal
                        ? `${import.meta.env.BASE_URL}${projects.link}`
                        : projects.link;

                    const sectionContent = (
                    <section
                        className={`flex flex-col gap-1 py-3 px-2 transition-all rounded-md sm:min-h-[6rem] lg:min-h-[8rem] overflow-hidden
                        ${isClickable ? 'hover:shadow-md hover:shadow-purple-800 border-b-4 border-purple-500' : 'opacity-50 cursor-not-allowed border-b-4 border-gray-400'}`}
                    >
                        <h1 className={`font-bold text-xl sm:text-3xl ${isClickable ? 'text-purple-800' : 'text-gray-500'}`}>
                        {t(`projects.title.${projects.title}`)}
                        </h1>
                        <div className="flex flex-wrap gap-5 sm:gap-14 mb-2">
                        <p className={
                            projects.status === 'Completed' ? 'text-green-500 font-semibold' :
                            projects.status === 'In progress' ? 'text-yellow-500 font-semibold' :
                            'text-red-500 font-semibold'
                        }>
                            {t(`projects.FliterStatus.${projects.status}`)}
                        </p>
                        <p className="text-neutral-400 italic">{projects.languages}</p>
                        </div>
                        <h3 className="text-sm sm:text-base line-clamp-2">
                        {t(`projects.description.${projects.description}`)}
                        </h3>
                    </section>
                    );

                    if (!isClickable) {
                    return <div key={index}>{sectionContent}</div>;
                    }

                    return (
                    <a
                        href={href}
                        target="_blank"
                        rel="noopener noreferrer"
                        key={index}
                    >
                        {sectionContent}
                    </a>
                    );
                })}
                </div>





        </section>
    );
}

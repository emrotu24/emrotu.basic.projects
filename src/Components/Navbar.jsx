import { useState } from "react";
import { NavLink } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { HiMenu, HiX } from "react-icons/hi";
import LanguageSwitcher from "./LanguageSwitcher";
import DropdownMenu from "./DropdownMenu";
import KobeBryant from "../medias/KobeBryant.png";

export default function Navbar() {
  const { t } = useTranslation();
  const [menuOpen, setMenuOpen] = useState(false);

  return (
    
    <nav className="bg-purple-800 text-white top-0 text-sm lg:text-lg px-5 flex items-center justify-between fixed w-full z-50 h-auto lg:h-24">
        {/* Titolo + Immagine */}
        <div className="flex gap-1 items-center">
          <div>
            <h1 className=" font-extrabold text-yellow-400 text-lg lg:text-4xl tracking-wide">
              {t("navbar.title")}
            </h1>
            <span className="text-xs lg:text-lg italic text-black tracking-widest font-bold font-signature">
              {t("navbar.theme")}
            </span>
          </div>
          <img
            src={KobeBryant}
            alt="Kobe Bryant"
            className="h-14 lg:h-24 object-cover bg-center bg-no-repeat"
          />
        </div>

        {/* Language Switcher (desktop only) */}
        <div className="hidden md:flex gap-2 justify-center">
          <LanguageSwitcher />
        </div>

        {/* Hamburger toggle (md and below) */}
        <button
          className="lg:hidden text-white text-2xl"
          onClick={() => setMenuOpen(!menuOpen)}
          aria-label="Toggle menu"
        >
          {menuOpen ? <HiX /> : <HiMenu />}
        </button>

      {/* Menu links */}
      <ul
        className={`absolute lg:relative flex-col right-0 top-10 lg:top-0 py-2 px-2 bg-purple-800 lg:flex-row lg:flex text-base xl:text-lg gap-2 xl:gap-8 items-center justify-end ${
          menuOpen ? "flex" : "hidden"
        } lg:flex mt-2 lg:mt-0`}
      >
        <li>
          <NavLink
            to="/"
            className={({ isActive }) =>
              `px-2 py-1 rounded ${isActive ? "text-yellow-400 cursor-default" : "hover:text-yellow-400 hover:bg-purple-700 transition"}`
            }
            onClick={(e) => {
              if (window.location.pathname === "/") e.preventDefault();
            }}
          >
            {t("navbar.homepage")}
          </NavLink>
        </li>

        <li>
          <NavLink
            to="/history"
            className={({ isActive }) =>
              `px-2 py-1 rounded ${isActive ? "text-yellow-400 cursor-default" : "hover:text-yellow-400 hover:bg-purple-700 transition"}`
            }
            onClick={(e) => {
              if (window.location.pathname === "/history") e.preventDefault();
            }}
          >
            {t("navbar.history")}
          </NavLink>
        </li>

        <li>
          <NavLink
            to="/projects"
            className={({ isActive }) =>
              `px-2 py-1 rounded ${isActive ? "text-yellow-400 cursor-default" : "hover:text-yellow-400 hover:bg-purple-700 transition"}`
            }
            onClick={(e) => {
              if (window.location.pathname === "/projects") e.preventDefault();
            }}
          >
            {t("navbar.projects")}
          </NavLink>
        </li>

        <li>
          <DropdownMenu />
        </li>

        {/* Language Switcher (mobile only) */}
        <li className="md:hidden">
          <LanguageSwitcher />
        </li>
      </ul>
    </nav>
  );
}

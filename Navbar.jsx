import React, { useState, useEffect } from "react";
import { NavLink, useLocation } from "react-router-dom";

const Navbar = () => {
 const location = useLocation();
 const [activePath, setActivePath] = useState(
  location.pathname
 );

 useEffect(() => {
  setActivePath(location.pathname);
 }, [location]);

 function activeNavLinkStyle(isActive) {
  return isActive
   ? "text-yellow-300 font-extrabold text-lg text-underscore"
   : "text-white font-bold text-lg";
 }

 const getNavbarStyle = () => {
  return activePath === "/"
   ? "bg-blue-800"
   : activePath === "/goals"
   ? "bg-green-800"
   : activePath === "/history"
   ? "bg-red-800"
   : activePath === "/projects"
   ? "bg-purple-800"
   : "bg-blue-800";
 };

 return (
  <nav className={`${getNavbarStyle()} p-3`}>
   <div className='container mx-auto flex justify-between items-center'>
    <NavLink
     to='/'
     className={({ isActive }) =>
      activeNavLinkStyle(isActive)
     }>
     Home
    </NavLink>
    <NavLink
     to='/goals'
     className={({ isActive }) =>
      activeNavLinkStyle(isActive)
     }>
     Goals
    </NavLink>
    <NavLink
     to='/history'
     className={({ isActive }) =>
      activeNavLinkStyle(isActive)
     }>
     History
    </NavLink>
    <NavLink
     to='/projects'
     className={({ isActive }) =>
      activeNavLinkStyle(isActive)
     }>
     Projects
    </NavLink>
   </div>
  </nav>
 );
};

export default Navbar;

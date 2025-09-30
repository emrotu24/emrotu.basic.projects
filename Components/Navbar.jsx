
import React from 'react';
import { Link } from 'react-router-dom';
import DropdownMenu from './DropdownMenu';
import { useState } from 'react';


export default function Navbar() {

  const [open, setOpen] = useState(false);

  return (
    <nav className="bg-yellow-600 text-white px-8 py-5 flex items-center justify-between shadow-lg">
      <div className="font-extrabold text-purple-800 text-2xl tracking-wide">EMANUELE ROTUNDI</div>
      <ul className="flex gap-10">
        <li><Link to="/" className="hover:text-purple-800 transition rounded-md">Home</Link></li>
        <li><Link to="/goals" className="hover:text-purple-800 transition rounded-md">Goals</Link></li>
        <li><Link to="/history" className="hover:text-purple-800 transition rounded-md">History</Link></li>
        <li><Link to="/projects" className="hover:text-purple-800 transition rounded-md">Projects</Link></li>
        <li><DropdownMenu />
        </li>
      </ul>
    </nav>
  );
}

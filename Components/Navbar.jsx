
import React from 'react';
import { Link } from 'react-router-dom';
import DropdownMenu from './DropdownMenu';
import { useState } from 'react';


export default function Navbar() {

  const [open, setOpen] = useState(false);

  return (
    <nav className="bg-purple-800 text-white px-8 py-5 flex items-center justify-between shadow-lg">
      <div className="font-extrabold text-yellow-600 text-4xl tracking-wide">EMANUELE ROTUNDI</div>
      <ul className="flex gap-10">
        <li><Link to="/" className="hover:text-yellow-600 text-lg transition rounded-md">Home</Link></li>
        <li><Link to="/goals" className="hover:text-yellow-600 text-lg transition rounded-md">Goals</Link></li>
        <li><Link to="/history" className="hover:text-yellow-600 text-lg transition rounded-md">History</Link></li>
        <li><Link to="/projects" className="hover:text-yellow-600 text-lg transition rounded-md">Projects</Link></li>
        <li><DropdownMenu />
        </li>
      </ul>
    </nav>
  );
}

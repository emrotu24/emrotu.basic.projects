
import React from 'react';
import { Link } from 'react-router-dom';

export default function Navbar() {
  return (
    <nav className="bg-yellow-600 text-white px-8 py-5 flex items-center justify-between shadow-lg">
      <div className="font-bold text-outline text-indigo-600 text-2xl tracking-wide">Emanuele Rotundi</div>
      <ul className="flex gap-10">
        <li><Link to="/" className="hover:text-indigo-600 transition px-3 py-2 rounded-md">Home</Link></li>
        <li><Link to="/goals" className="hover:text-indigo-600 transition px-3 py-2 rounded-md">Goals</Link></li>
        <li><Link to="/history" className="hover:text-indigo-600 transition px-3 py-2 rounded-md">History</Link></li>
        <li><Link to="/projects" className="hover:text-indigo-600 transition px-3 py-2 rounded-md">Projects</Link></li>
      </ul>
    </nav>
  );
}

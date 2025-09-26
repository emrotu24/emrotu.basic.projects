
import React from 'react';
import { Link } from 'react-router-dom';

export default function Navbar() {
  return (
    <nav className="bg-blue-700 text-white px-8 py-5 flex items-center justify-between shadow-lg">
      <div className="font-bold text-2xl tracking-wide">Emanueleee Rotundi Projects</div>
      <ul className="flex gap-10">
        <li><Link to="/" className="hover:text-yellow-300 transition px-3 py-2 rounded-md">Home</Link></li>
        <li><Link to="/goals" className="hover:text-yellow-300 transition px-3 py-2 rounded-md">Goals</Link></li>
        <li><Link to="/history" className="hover:text-yellow-300 transition px-3 py-2 rounded-md">History</Link></li>
        <li><Link to="/projects" className="hover:text-yellow-300 transition px-3 py-2 rounded-md">Projects</Link></li>
      </ul>
    </nav>
  );
}

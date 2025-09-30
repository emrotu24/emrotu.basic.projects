import { useState, useRef, useEffect } from 'react';

export default function DropdownMenu({ onClose }) {
  const [open, setOpen] = useState(false);
  const menuRef = useRef(null);

   // Chiudi il menu se clicchi fuori
  useEffect(() => {
    function handleClickOutside(event) {
      if (menuRef.current && !menuRef.current.contains(event.target)) {
        setOpen(false);
      }
    }

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <div className="bg-white rounded-md shadow-lg" ref={menuRef}>
      <button
        onClick={() => setOpen(!open)}
        className="bg-purple-800 hover:bg-purple-600 text-white px-4 rounded-md">Contatti
      </button>

      {/* Menu a tendina */}
      {open && (
        <div className="absolute mt-2 w-56 bg-white rounded-md shadow-lg z-10">
            <ul className="py-1">
                <li>
                <a
                    href="https://wa.me/634260796"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                    onClick={onClose}>WhatsApp
                </a>
                </li>
                <li>
                <a
                    href="mailto:emmanuelcircle0@gmail.com"
                    className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                    onClick={onClose}>Email
                </a>
                </li>
                <li>
                <a
                    href="https://linkedin.com/in/emanuele-rotundi-a13a4a284"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                    onClick={onClose}>LinkedIn
                </a>
                </li>
                <li>
                <a
                    href="https://github.com/emrotu24"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                    onClick={onClose}>Github
                </a>
                </li>
            </ul>
       </div>
      )}
    </div>
  );
}
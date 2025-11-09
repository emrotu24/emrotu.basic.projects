import { useState, useRef, useEffect } from 'react';
import { useTranslation } from 'react-i18next';

export default function DropdownMenu({ onClose }) {
  const [open, setOpen] = useState(false);
  const contactsButtonRef = useRef(null);
  const contactsMenuRef = useRef(null);

  const { t } = useTranslation();

  // Chiudi il menu se clicchi fuori
  useEffect(() => {
    function handleClickOutside(event) {
      if (contactsMenuRef.current && !contactsMenuRef.current.contains(event.target) && contactsButtonRef.current && !contactsButtonRef.current.contains(event.target)) {
        setOpen(false);
      }
    }

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <section>
      <button
        ref={contactsButtonRef}
        onClick={() => setOpen(!open)}
        className="bg-yellow-400 hover:bg-yellow-300 text-black px-2 text-base xl:text-lg rounded-md">{t("dropdown.contact")}
      </button>

      {/* Menu a tendina */}
      {open && (
        <ul className="py-1 absolute text-base xl:text-lg mt-1 right-6 bg-white rounded-md shadow-lg z-10" ref={contactsMenuRef}>
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
      )}
    </section>
  );
}
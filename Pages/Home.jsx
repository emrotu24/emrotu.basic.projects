import React from 'react';
import Introduction from '../Components/Introduction';
import Contacts from '../Components/Contacts';
import Footer from '../Components/Footer';

export default function Home() {
  return(
    <>
      <main>
        <Introduction />
        <Contacts />
        <Footer />
      </main>
    </>
  )
}

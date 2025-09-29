import React from 'react';
import Introduction from '../Components/Introduction';
import Contacts from '../Components/Contacts';
import Footer from '../Components/Footer';
import Overview from '../Components/Overview';

export default function Home() {
  return(
    <>
      <main>
        <Introduction />
        <Overview />
        <Contacts />
        <Footer />
      </main>
    </>
  )
}

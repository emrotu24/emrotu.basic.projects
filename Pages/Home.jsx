import React from 'react';
import Introduction from '../Components/Introduction';
import Footer from '../Components/Footer';
import Overview from '../Components/Overview';

export default function Home() {
  return(
    <>
      <main>
        <Introduction />
        <Overview />
        <Footer />
      </main>
    </>
  )
}

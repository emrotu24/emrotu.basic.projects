import React from "react";
import {
 BrowserRouter as Router,
 Route,
 Routes,
} from "react-router-dom";
import Navbar from "./src/Components/Navbar";
import Home_Page from "./src/Pages/Home_Page";
import Goals from "./src/Pages/Goals";
import History_Page from "./src/Pages/History_Page";
import Projects_Page from "./src/Pages/Projects_Page";
import './i18n';


function App() {
 return (
  <Router basename="/emrotu.basic.projects">
   <Navbar />
   <Routes>
    <Route
     path='/'
     element={<Home_Page />}
    />
    <Route
     path='/goals'
     element={<Goals />}
    />
    <Route
     path='/history'
     element={<History_Page />}
    />
    <Route
     path='/projects'
     element={<Projects_Page />}
    />
   </Routes>
  </Router>
 );
}

export default App;

import React from "react";
import {
 BrowserRouter as Router,
 Route,
 Routes,
} from "react-router-dom";
import Navbar from "./Components/Navbar";
import Home from "./Pages/Home";
import Goals from "./Pages/Goals";
import History from "./Pages/History";
import Projects from "./Pages/Projects";

function App() {
 return (
  <Router>
   <Navbar />
   <Routes>
    <Route
     path='/'
     element={<Home />}
    />
    <Route
     path='/goals'
     element={<Goals />}
    />
    <Route
     path='/history'
     element={<History />}
    />
    <Route
     path='/projects'
     element={<Projects />}
    />
   </Routes>
  </Router>
 );
}

export default App;

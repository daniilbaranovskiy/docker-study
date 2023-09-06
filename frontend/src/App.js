import logo from './logo.svg';
import {Link, link, Route, Routes, useParams} from "react-router-dom";
import './App.css';
import Header from "./components/Header";
import Counter from "./components/Counter";
import Features from "./components/Features";

function App() {
    return (
        <div>
            <h1>Router Example</h1>
            <p>Navigation</p>
            <ul>
                <li><Link to="/header">Header</Link> </li>
                <li><Link to="/counter">Counter</Link></li>
                <li><Link to="/features">Features</Link></li>
            </ul>
            <Routes>
                <Route path="/header?/:headerId" element={<Header/>}></Route>
                <Route path="/counter" element={<Counter/>}></Route>
                <Route path="/features" element={<Features/>}></Route>
            </Routes>
        </div>
    );
}

export default App;

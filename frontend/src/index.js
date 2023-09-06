import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import reportWebVitals from './reportWebVitals';
import {BrowserRouter as Router} from "react-router-dom";
import App from "./App";

const root = ReactDOM.createRoot(document.getElementById('root'));
const arr = [2, 10, 23, 1, 10]
root.render(
    <React.StrictMode>
        <Router>
            <App></App>
        </Router>
    </React.StrictMode>
);

reportWebVitals();

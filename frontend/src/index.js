import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import reportWebVitals from './reportWebVitals';
import Counter from "./components/Counter";

const root = ReactDOM.createRoot(document.getElementById('root'));
const arr = [2, 10, 23, 1, 10]
root.render(
    <React.StrictMode>
        {arr.map(
            (value, index) =>
                <Counter key={index} color={'red'}></Counter>
        )}
    </React.StrictMode>
);

reportWebVitals();

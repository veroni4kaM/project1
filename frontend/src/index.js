import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import Counter from "./components/Counter";


const root = ReactDOM.createRoot(document.getElementById('root'));
const arr = [2, 10,23,1];
root.render(
  <React.StrictMode>
    {arr.map(
        (value,index)=>
            <Counter key={index}/>)
    }
  </React.StrictMode>
);
reportWebVitals();

import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import Page from "./components/Page";
import Counter from "./components/Counter";
import PropTypes from "prop-types";

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

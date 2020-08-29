import React, { Component } from 'react';
import ReactDom from 'react-dom';

import Basket from "./react.components/basket/basket";
import './style.scss';

document.addEventListener('DOMContentLoaded', () => {
    ReactDom.render(<Basket />, document.getElementById('sidePanel-basket'));
});



import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import TestPage from './testPage';
import registerServiceWorker from './registerServiceWorker';

ReactDOM.render(<TestPage />, document.getElementById('root'));
registerServiceWorker();
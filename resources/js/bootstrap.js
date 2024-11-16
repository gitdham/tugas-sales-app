import axios from 'axios';
import idrFormat from './idrFormat';
window.axios = axios;
window.idrFormat = idrFormat;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


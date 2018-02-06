
require('./bootstrap');

window.Vue = require('vue');
window.Slug = require('slug');
Slug.defaults.mode = 'rfc3986';

import Buefy from 'buefy';
import ActivityGraph from './components/activity-graph/ActivityGraph'
import axios from "axios";


axios.interceptors.request.use(function(config){
    config.headers['X-CSRF-TOKEN'] = window.Laravel.csrfToken
    config.headers['APP'] = 'Inferno'
    return config
})

Vue.use(Buefy);
Vue.use(axios)




Vue.component('slugWidget', require('./components/slugWidget.vue'));
Vue.component('laratoaster', require('./components/LaraToaster.vue'));
Vue.component('activity-graph', ActivityGraph);

const app2 = new Vue({
    el: '#app-2',
    data: {}
});

require('./manage.js');

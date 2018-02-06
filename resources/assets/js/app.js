
require('./bootstrap');

window.Vue = require('vue');
window.Slug = require('slug');
Slug.defaults.mode = 'rfc3986';

import Buefy from 'buefy';
import ActivityGraph from './components/activity-graph/ActivityGraph'
Vue.use(Buefy);


Vue.component('slugWidget', require('./components/slugWidget.vue'));
Vue.component('laratoaster', require('./components/LaraToaster.vue'));
Vue.component('activity-graph', ActivityGraph);

const app2 = new Vue({
    el: '#app-2',
    data: {}
});

const app = new Vue({
    el: '#app',
    data: {}
});

require('./manage.js');

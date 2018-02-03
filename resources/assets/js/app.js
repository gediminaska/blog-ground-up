
require('./bootstrap');

window.Vue = require('vue');
window.Slug = require('slug');
Slug.defaults.mode = 'rfc3986';

import Buefy from 'buefy'
Vue.use(Buefy);


Vue.component('slugWidget', require('./components/slugWidget.vue'));

var app2 = new Vue({
    el: '#app-2',
    data: {}
});

require('./manage.js');

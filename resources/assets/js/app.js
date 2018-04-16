
require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import axios from "axios";
import moment from "moment";
import VueMomentJS from "vue-momentjs";
import InstantSearch from 'vue-instantsearch';

Vue.use(Buefy);
Vue.use(axios);
Vue.use(VueMomentJS, moment);
Vue.use(InstantSearch);


Vue.component('laratoaster', require('./components/LaraToaster.vue'));

const app2 = new Vue({
    el: '#app-2',
    data: {}
});

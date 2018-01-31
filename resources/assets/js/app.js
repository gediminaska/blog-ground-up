
require('./bootstrap');

window.Vue = require('vue');
window.Slug = require('slug');
Slug.defaults.mode = 'rfc3986';

import Buefy from 'buefy'
Vue.use(Buefy);

Vue.component('slugWidget', require('./components/slugWidget.vue'));

var app = new Vue({
  el: '#app',
  data: {
      autoPassword: true,
      password_options: 'keep',
      permissionType: 'basic',
      resource: '',
      crudSelected: ['create', 'read', 'update', 'delete']
  },
    methods: {
        crudName: function(item) {
            return item.substr(0,1).toUpperCase() + item.substr(1) + " " + app.resource.substr(0,1).toUpperCase() + app.resource.substr(1);
        },
        crudSlug: function(item) {
            return item.toLowerCase() + "-" + app.resource.toLowerCase();
        },
        crudDescription: function(item) {
            return "Allow a User to " + item.toUpperCase() + " a " + app.resource.substr(0,1).toUpperCase() + app.resource.substr(1);
        }
    }
});

var app2 = new Vue({
    el: '#app-2',
    data: {}
});


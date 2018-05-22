
window.Slug = require('slug');
Slug.defaults.mode = 'rfc3986';
import ActivityGraph from './components/activity-graph/ActivityGraph'

Vue.component('activity-graph', ActivityGraph);
Vue.component('slugWidget', require('./components/slugWidget.vue'));
Vue.component('tagSlugWidget', require('./components/tagSlugWidget.vue'));
Vue.component('wysiwyg', require('./components/Wysiwyg.vue'));


const accordions = document.getElementsByClassName('has-submenu');
const adminSlideoutButton = document.getElementById('admin-slideout-button')

adminSlideoutButton.onclick = function () {
    this.classList.toggle('is-active');
    document.getElementById('admin-side-menu').classList.toggle('is-active')
}

for (var i = 0; i < accordions.length; i++) {
    if(accordions[i].classList.contains('is-active')) {
        const submenu = accordions[i].nextElementSibling;
        submenu.style.maxHeight = submenu.scrollHeight + "px";
        submenu.style.marginTop = "0.75rem";
        submenu.style.marginBottom = "0.75rem"
    }
    accordions[i].onclick = function () {
        // this.classList.toggle('is-active');

        const submenu = this.nextElementSibling;
        if(submenu.style.maxHeight) {
            //if the menu is open
            submenu.style.maxHeight = null;
            submenu.style.marginTop = null;
            submenu.style.marginBottom = null
        } else {
            //in the menu is closed
            submenu.style.maxHeight = submenu.scrollHeight + "px";
            submenu.style.marginTop = "0.75rem";
            submenu.style.marginBottom = "0.75rem"
        }
    }
}


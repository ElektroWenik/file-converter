import 'vuetify/dist/vuetify.min.css'

import Vue from 'vue';
import Vuetify from 'vuetify';
import VueUpload from '@websanova/vue-upload';
import VeeValidate from 'vee-validate';
import VueAxios from 'vue-axios'
import axios from 'axios'


Vue.use(VueAxios, axios);
Vue.use(Vuetify);
Vue.use(VueUpload);
Vue.use(VeeValidate);


// Components
import Converter from './components/Converter.vue';

Vue.component('converter', Converter);

const app = new Vue({
    el: '#app'
});

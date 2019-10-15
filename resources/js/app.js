require('./bootstrap');

window.Vue       = require('vue');
window.VueRouter = require('vue-router').default;
window.VueAxios  = require('vue-axios').default;
window.Axios     = require('axios').default;

import 'vue-loading-overlay/dist/vue-loading.css';
import 'vue-datetime/dist/vue-datetime.css';
import VueMomentLib  from "vue-moment-lib";
import App           from './components/App.vue';
import Loading       from 'vue-loading-overlay';
import VueOnToast    from 'vue-on-toast';
import VuePaginate   from 'vue-paginate';
import VueToast      from 'vue-toast-notification';
import Datetime      from 'vue-datetime'

//Registro de complementos
 
Vue.use(Datetime);
Vue.use(VuePaginate);
Vue.use(Loading);
Vue.use(VueMomentLib);
Vue.use(VueOnToast);

Vue.component('App-component', require('./components/App.vue')); 

//Registro de componentes 

const Menu           =  Vue.component('Menu', require('./components/Menu.vue').default);
const Header         =  Vue.component('Header', require('./components/Header.vue').default); 
const Bitacora       =  Vue.component('Bitacora', require('./Bitacora/Bitacora.vue').default); 
const Historicos     =  Vue.component('Historicos', require('./Historicos/Historicos.vue').default); 
const Boveda         =  Vue.component('Boveda', require('./Boveda/Boveda.vue').default); 
const Lco            =  Vue.component('Lco', require('./Lco/Lco.vue').default); 
const Reportes       =  Vue.component('Reportes', require('./Reportes/Reportes.vue').default); 
const Facturas       =  Vue.component('Facturas', require('./Facturas/Facturas.vue').default); 
const Inscritos      =  Vue.component('Facturas', require('./Lco/Inscritos.vue').default); 

Vue.use(VueRouter, VueAxios, axios);

const routes = [
    {
        name: 'Menu',
        path: '/l-s',
        component: Menu,
    },
    {
        name: 'Header',
        path: '/header',
        component: Header,
    },
    {
        name: 'Bitacora',
        path: '/tboreport/public/Bitacora',
        component: Bitacora,
    },
    {
        name: 'Historicos',
        path: '/tboreport/public/dashboard',
        component: Historicos,
    },
    {
        name: 'Boveda',
        path: '/tboreport/public/Boveda',
        component: Boveda,
    },
    {
        name: 'Lco',
        path: '/tboreport/public/Lco',
        component: Lco,
    },
    {
        name: 'Reportes',
        path: '/tboreport/public/Reportes',
        component: Reportes,
    },
    {
        name: 'Facturas',
        path: '/tboreport/public/Facturas',
        component: Facturas,
    },
    {
        name: 'Inscritos',
        path: '/tboreport/public/Inscritos',
        component: Inscritos,
    },
];



const router = new VueRouter({ mode: 'history', routes: routes});

new Vue(Vue.util.extend({ router }, App)).$mount('#app');


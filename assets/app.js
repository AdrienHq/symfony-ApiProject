import './styles/app.css';
import './bootstrap';
import Vue from 'vue';
import LibraryApp from '/assets/js/component/LibraryApp.vue';

Vue.component('library-app', LibraryApp);

const app = new Vue({
    el: '#library-app'
});
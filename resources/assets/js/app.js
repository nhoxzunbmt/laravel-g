require('./bootstrap');

/**
 * We will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import VueRouter  from 'vue-router'
import router     from './router'
import Vue        from 'vue'
import VueResource from 'vue-resource';

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
Vue.http.options.root = 'http://games.dev';

export default Vue;

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Signin from './components/Signin.vue';

// lazy load components
const Room = require('./components/Room.vue')
const PopularGame = require('./components/PopularGame.vue')

// Create and mount root instance.
// Make sure to inject the router.
// Route components will be rendered inside <router-view>.
/*new Vue({
    
    router,
    
    components : {
        Room,
        PopularGame
    },
    
    data : {
    
    }
 
}).$mount('#app')*/
new Vue({
    el: '#app',
    router: router,
    render: app => app(App)
});
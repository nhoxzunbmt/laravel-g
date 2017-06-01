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

import { HasError4, AlertError, AlertSuccess } from 'vform';
Vue.component(HasError4.name, HasError4)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertSuccess.name, AlertSuccess)

Vue.use(VueRouter);
Vue.use(VueResource);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
Vue.http.options.root = 'http://games.dev';

/**
**  Filters
***/
var truncateFilter = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', truncateFilter);

export default Vue;

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Signin from './components/Signin.vue';

// lazy load components
const Games = require('./components/Games.vue')

//Meta change
router.beforeEach((to, from, next) => {
    
    if(to.meta.title!==undefined)
    {
        document.title = to.meta.title;
    }
    
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (to.matched.some(record => record.meta.requiresAuth)) 
    {
        let token = localStorage.getItem('id_token')
        if (token === null) {
            next({
                path: '/signin',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

// Create and mount root instance.
// Make sure to inject the router.
// Route components will be rendered inside <router-view>.
new Vue({
    router,
    components : {
        Games
    },
    data : {
    
    },
    render: app => app(App)
}).$mount('#app')
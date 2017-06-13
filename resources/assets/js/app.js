require('./bootstrap');

/**
 * We will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import VueRouter  from 'vue-router'
import Meta from 'vue-meta'
import router     from './router'
import Vue        from 'vue'
import VueResource from 'vue-resource'
export default Vue;

Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(Meta)

Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementsByName('csrf-token')[0].getAttribute('content');
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
//Vue.http.options.root = 'http://games.dev';

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

window.Event = new class {
    
    constructor() {
        this.vue = new Vue()
    }
    
    fire(event, data = null) {
        this.vue.$emit(event, data)
    }
    
    listen(event, callback) {
        this.vue.$on(event, callback)
    }
}

//Event.fire('applied')
//Event.listen('applied', () => alert('Handling it')))

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Games from './components/Games.vue';

//Meta change
router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) 
    {
        if (localStorage.getItem('id_token') === null) {
            next({
                path: '/login',
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
new Vue({
    router,
    components : {
        Games
    },
    data : {
    
    },
    render: app => app(App)
}).$mount('#app')
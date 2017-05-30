import VueRouter from 'vue-router'

//Define route components
const Foo = { template: '<div>Foo</div>' }
const Bar = { template: '<div>Bar</div>' }

// lazy load components
const Room = require('./components/Room.vue')

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Signin from './components/Signin.vue';

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    saveScrollPosition: true,
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        { path: '/foo', component: Foo },
        { path: '/bar', component: Bar },
        { path: '/rooms', component: Room }, // example of route with a seperate component
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/signin',
            name: 'signin',
            component: Signin
        }
    ]
});
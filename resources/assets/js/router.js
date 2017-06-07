import VueRouter from 'vue-router'

// lazy load components
const Games = require('./components/Games.vue')
const Game = require('./components/Game.vue')

const NotFoundComponent = require('./pages/errors/404.vue')

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import SocialCallback from './components/SocialCallback.vue';

import StreamDetail from './components/StreamDetail.vue';


export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: {
                title: 'Home Page'
            },
            redirect: { name: 'games' }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                title: 'Register Page'
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                title: 'Login Page'
            }
        },
        {
            path: '/social/:socialId/callback',
            name: 'social.callback',
            component: SocialCallback
        },
        { 
            path: '/password/email', 
            name: 'password.request', 
            component: require('./components/Password/Email.vue')
        },
        { 
            path: '/password/reset/:token', 
            name: 'password.reset', 
            component: require('./components/Password/Reset.vue') 
        },
        {
            path: '/games',
            name: 'games',
            component: Games,
            meta: {
                title: 'Games list',
                //requiresAuth: true
            }
        },
        {
            path: '/games/:gameId',
            name: 'game',
            component: Game
        },
        {
            path: '/streams/:stream',
            name: 'stream',
            component: StreamDetail
        },
        {
            path: '/profile',
            name: 'profile',
            component: require('./components/Personal/Profile.vue'),
            meta: {
                title: 'Profile',
                requiresAuth: true
            }
        },
        { 
            path: '*',
            component: NotFoundComponent 
        }
    ]
});
import VueRouter from 'vue-router'

// lazy load components
const Games = require('./components/Games.vue')
const Game = require('./components/Game.vue')

const NotFoundComponent = require('./pages/errors/404.vue')

import App from './components/App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Signin from './components/Signin.vue';
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
            path: '/signin',
            name: 'signin',
            component: Signin,
            meta: {
                title: 'Sigin Page'
            }
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
            path: '*',
            component: NotFoundComponent 
        }
    ]
});
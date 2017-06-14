import { authGuard, guestGuard } from './util/router'

export default [
  { path: '/', name: 'home', component: require('./pages/home.vue'), redirect: { name: 'games' } },
  {
        path: '/games',
        name: 'games',
        component: require('./pages/games/index.vue')
  },
    {
        path: '/games/:gameId',
        name: 'game',
        component: require('./pages/games/detail.vue')
    },
    {
        path: '/streams/:stream',
        name: 'stream',
        component: require('./pages/streams/detail.vue')
    },
    {
        path: '/faq',
        name: 'faq',
        component: require('./pages/faq.vue')
    },
    
  ...authGuard([
    {
        path: '/profile',
        name: 'profile',
        component: require('./pages/personal/profile.vue')
    },
    { path: '/settings', component: require('./pages/settings/account.vue'), children: [
      { path: '', redirect: { name: 'settings.profile' }},
      { path: 'profile', name: 'settings.profile', component: require('./pages/settings/_profile.vue') },
      { path: 'security', name: 'settings.security', component: require('./pages/settings/_security.vue') }
    ] }
  ]),

  ...guestGuard([
    {
        path: '/social/:socialId/callback',
        name: 'social.callback',
        component: require('./pages/auth/socialcallback.vue')
    },
    { path: '/auth/login', name: 'auth.login', component: require('./pages/auth/login.vue') },
    { path: '/auth/register', name: 'auth.register', component: require('./pages/auth/register.vue') },
    { path: '/password/reset', name: 'password.request', component: require('./pages/auth/password/email.vue') },
    { path: '/password/reset/:token', name: 'password.reset', component: require('./pages/auth/password/reset.vue') }
  ]),

  { path: '*', component: require('./pages/errors/404.vue') }
]

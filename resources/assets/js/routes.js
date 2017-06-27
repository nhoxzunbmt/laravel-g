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
    {
        path: '/players',
        name: 'players',
        component: require('./pages/players/index.vue')
    },
    {
        path: '/players/:id',
        name: 'player',
        component: require('./pages/players/detail.vue')
    },
    {
        path: '/investors',
        name: 'investors',
        component: require('./pages/investors/index.vue')
    },
    {
        path: '/investors/:id',
        name: 'investor',
        component: require('./pages/investors/detail.vue')
    },
    {
        path: '/fights',
        name: 'fights',
        component: require('./pages/fights/index.vue')
    },
    
  ...authGuard([
    {
        path: '/profile',
        name: 'profile',
        component: require('./pages/personal/profile.vue')
    },
    { path: '/friends', component: require('./pages/personal/friends/index.vue'), children: [
        { path: '', redirect: { name: 'friends.all' }},
        { path: 'all', name: 'friends.all', component: require('./pages/personal/friends/_all.vue') },
        { path: 'online', name: 'friends.online', component: require('./pages/personal/friends/_online.vue') },
        { path: 'requests-in', name: 'friends.request.in', component: require('./pages/personal/friends/_request_in.vue') },
        { path: 'requests-out', name: 'friends.request.out', component: require('./pages/personal/friends/_request_out.vue') },
        { path: 'search', name: 'friends.search', component: require('./pages/personal/friends/_search.vue') }
    ] },
    {
        path: '/teams/create',
        name: 'teams.create',
        component: require('./pages/teams/create.vue')
    },
    {
        path: '/teams/:id/edit',
        name: 'teams.edit',
        component: require('./pages/teams/edit.vue')
    },
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
    { path: '/password/reset/:token', name: 'password.reset', component: require('./pages/auth/password/reset.vue') },
    { path: '/auth/verify/:token', name: 'auth.verify', component: require('./pages/auth/verify.vue') }
  ]),
    
  {
    path: '/teams',
    name: 'teams',
    component: require('./pages/teams/index.vue'),
    children: [
        {
            path: ':slug',
            name: 'team',
            component: require('./pages/teams/detail.vue')
        }
    ]
  },  
  { path: '*', component: require('./pages/errors/404.vue') }
]

import { authGuard, guestGuard } from './util/router'

export default [
  { path: '/', name: 'home', component: require('./pages/home.vue'), redirect: { name: 'games' } },
  {
        path: '/games',
        name: 'games',
        component: require('./pages/games/index.vue')
  },
    /*{
        path: '/games/:gameId',
        name: 'game',
        component: require('./pages/games/detail/index.vue')
    },*/
    
    { path: '/games/:gameId', component: require('./pages/games/detail/index.vue'), children: [
        { path: '', redirect: { name: 'game' }},
        { path: 'broadcasts', name: 'game', component: require('./pages/games/detail/_broadcasts.vue') },
        { path: 'info', name: 'game.info', component: require('./pages/games/detail/_info.vue') }
    ] },
    {
        path: '/streams/',
        name: 'streams',
        component: require('./pages/streams/index.vue')
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
        path: '/personal',
        component: require('./pages/personal/index.vue'), children: [
            { path: '', redirect: { name: 'profile' }},
            { path: 'info', name: 'profile', component: require('./pages/personal/profile.vue') },
            { path: 'teams', name: 'personal.teams', component: require('./pages/personal/teams.vue') },
            
            { path: 'friends', component: require('./pages/personal/friends/index.vue'), children: [
                { path: '', redirect: { name: 'friends.all' }},
                { path: 'all', name: 'friends.all', component: require('./pages/personal/friends/_all.vue') },
                { path: 'online', name: 'friends.online', component: require('./pages/personal/friends/_online.vue') },
                { path: 'requests-in', name: 'friends.request.in', component: require('./pages/personal/friends/_request_in.vue') },
                { path: 'requests-out', name: 'friends.request.out', component: require('./pages/personal/friends/_request_out.vue') },
                { path: 'search', name: 'friends.search', component: require('./pages/personal/friends/_search.vue') }
            ] },
            
        ]
    },
    {
        path: '/teams/create',
        name: 'teams.create',
        component: require('./pages/teams/create.vue')
    },
    {
        path: '/fights/create',
        name: 'fights.create',
        component: require('./pages/fights/create.vue')
    },
    { path: '/teams/:id/edit', component: require('./pages/teams/edit/index.vue'), children: [
        { path: '', redirect: { name: 'teams.edit' }},
        { path: 'info', name: 'teams.edit', component: require('./pages/teams/edit/_info.vue') },
        { path: 'players', name: 'teams.edit.players', component: require('./pages/teams/edit/_players.vue') }
    ]},
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
    component: require('./pages/teams/index.vue')
  },  
  
  { path: '/teams/:slug', component: require('./pages/teams/detail/index.vue'), children: [
        { path: '', redirect: { name: 'team.detail' }},
        { path: 'info', name: 'team.detail', component: require('./pages/teams/detail/_info.vue') },
        { path: 'players', name: 'team.detail.players', component: require('./pages/teams/detail/_players.vue') }
  ]},
  
  { path: '*', component: require('./pages/errors/404.vue') }
]

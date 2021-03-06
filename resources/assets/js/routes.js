import { authGuard, guestGuard } from './util/router'

export default [
  { path: '/', name: 'home', component: require('./pages/home.vue')/*, redirect: { name: 'games' }*/ },
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
    {
        path: '/news',
        name: 'news',
        component: require('./pages/news/index.vue')
    },
    {
        path: '/news/:slug',
        name: 'news.detail',
        component: require('./pages/news/detail.vue')
    },
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
    
    { path: '/players/:id', component: require('./pages/players/detail/index.vue'), children: [
        { path: '',  name: 'player', redirect: { name: 'player.detail' }},
        { path: 'info', name: 'player.detail', component: require('./pages/players/detail/_info.vue') },
        { path: 'teams', name: 'player.detail.teams', component: require('./pages/players/detail/_teams.vue') },
        { path: 'fights', name: 'player.detail.fights', component: require('./pages/players/detail/_fights.vue') },
        { path: 'schedule', name: 'player.detail.schedule', component: require('./pages/players/detail/_schedule.vue') }        
    ]},

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
        path: '/investors-info',
        name: 'investors-info',
        component: require('./pages/investors-info.vue')
    },
    
  ...authGuard([
    {
        path: '/personal',
        component: require('./pages/personal/index.vue'), children: [
            { path: '', redirect: { name: 'profile' }},
            { path: 'info', name: 'profile', component: require('./pages/personal/profile.vue') },
            { path: 'teams', name: 'personal.teams', component: require('./pages/personal/teams.vue') },
            { path: 'calendar', name: 'personal.calendar', component: require('./pages/personal/calendar.vue') },
            { path: 'stream', name: 'personal.stream', component: require('./pages/personal/stream.vue') },
            { path: 'billing', name: 'personal.billing', component: require('./pages/personal/billing.vue') },

            { path: 'friends', component: require('./pages/personal/friends/index.vue'), children: [
                { path: '', redirect: { name: 'friends.search' }},
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
    /*{
        path: '/fights/create',
        name: 'fights.create',
        component: require('./pages/fights/create.vue')
    },*/
    { path: '/teams/:id/edit', component: require('./pages/teams/edit/index.vue'), children: [
        { path: '', redirect: { name: 'teams.edit' }},
        { path: 'info', name: 'teams.edit', component: require('./pages/teams/edit/_info.vue') },
        { path: 'players', name: 'teams.edit.players', component: require('./pages/teams/edit/_players.vue') }
    ]},
    
    { path: '/fights', component: require('./pages/fights/index.vue'), children: [
        { path: '', name: 'fights', redirect: { name: 'fights.calendar' } },
        { path: 'calendar', name: 'fights.calendar', component: require('./pages/fights/_calendar.vue') },
        { path: 'confirmed', name: 'fights.confirmed', component: require('./pages/fights/_confirmed.vue') },
        { path: 'invitations_received', name: 'fights.invitations.received', component: require('./pages/fights/_invitations_received.vue') },
        { path: 'invitations_sent', name: 'fights.invitations.sent', component: require('./pages/fights/_invitations_sent.vue') },
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
    { path: '/password/reset/:token', name: 'password.reset', component: require('./pages/auth/password/reset.vue') }//,
    //{ path: '/email/verify/:token', name: 'auth.verify', component: require('./pages/auth/verify.vue') }
  ]),
    
  {
    path: '/teams',
    name: 'teams',
    component: require('./pages/teams/index.vue')
  },  
  
  { path: '/teams/:slug', component: require('./pages/teams/detail/index.vue'), children: [
        { path: '', redirect: { name: 'team.detail' }},
        { path: 'info', name: 'team.detail', component: require('./pages/teams/detail/_info.vue') },
        { path: 'players', name: 'team.detail.players', component: require('./pages/teams/detail/_players.vue') },
        { path: 'schedule', name: 'team.detail.schedule', component: require('./pages/teams/detail/_schedule.vue') }
  ]},
  
  /*{
        path: '/fights',
        name: 'fights',
        component: require('./pages/fights/index.vue')
  },*/
  {
        path: '/fights/:id',
        name: 'fight',
        component: require('./pages/fights/detail.vue')
  },
  
  { path: '/email/verify/:token', name: 'auth.verify', component: require('./pages/auth/verify.vue') },
  { path: '/player', name: 'player', component: require('./pages/player.vue') },
  { path: '*', component: require('./pages/errors/404.vue') }
]

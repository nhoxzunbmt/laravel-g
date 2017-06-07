import Vue from './app.js';
import router from './router';

export default {
    user: {
        authenticated: false,
        profile: null
    },
    check() 
    {
        let token = localStorage.getItem('id_token')
        if (token !== null) {
            Vue.http.get(
                'api/user?token=' + token
            ).then(response => {
                this.user.authenticated = true
                this.user.profile = response.data.data
            })
        }
    },
    register(context) 
    {
        Vue.http.post(
            'api/register',
            {
                name: context.name,
                email: context.email,
                password: context.password
            }
        ).then(response => {
            context.success = true
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    login(context) 
    {
        Vue.http.post(
            'api/login',
            {
                email: context.email,
                password: context.password
            }
        ).then(response => {
            context.error = false
            localStorage.setItem('id_token', response.data.meta.token)
            Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')

            this.user.authenticated = true
            this.user.profile = response.data.data

            router.push({
                name: 'home'
            })
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    forgotPassword(context) 
    {
        Vue.http.post(
            'api/password/email',
            {
                email: context.email
            }
        ).then(response => {
            context.success = true
            context.error = false
            context.status = response.data.status
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    resetPassword(context)
    {
        Vue.http.post(
            'api/password/reset',
            {
                token: context.token,
                email: context.email,
                password: context.password,
                password_confirmation: context.password_confirmation
            }
        ).then(response => {
            
            localStorage.removeItem('id_token')
            context.success = true
            context.error = false
            context.status = response.data.status
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    socialAuth(context)
    {
        Vue.http.get('api'+context.$route.fullPath).then(response => {
            context.error = false
            localStorage.setItem('id_token', response.data.meta.token)
            Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')

            this.user.authenticated = true
            this.user.profile = response.data.data

            router.push({
                name: 'home'
            })
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    signout() 
    {
        localStorage.removeItem('id_token')
        this.user.authenticated = false
        this.user.profile = null

        router.push({
            name: 'home'
        })
    }
}
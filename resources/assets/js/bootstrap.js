import Vue from 'vue'
import $ from 'jquery'
import Tether from 'tether'
import Meta from 'vue-meta'
import Router from 'vue-router'

import './components'
import './util/interceptors'

Vue.config.productionTip = false

Vue.use(Router)
Vue.use(Meta)

window.jQuery = $
window.Tether = Tether
require('bootstrap')

window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');
window.Vue = require('vue');
require('vue-resource');
require('select2');

var VueBreadcrumbs = require('vue2-breadcrumbs');
window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

/**
**  Event bus 
**/
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

import Echo from "laravel-echo"
import Pusher from 'pusher-js'

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'd9bb5a86510edebf23af',
    cluster: 'eu',
    encrypted: true
});
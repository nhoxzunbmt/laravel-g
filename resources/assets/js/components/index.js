import Vue from 'vue'
import Icon from './Icon.vue'
import Child from './Child.vue'
import Navigation from './Navigation.vue';
import Sidebar from './Sidebar.vue';
import { HasError4, AlertError, AlertSuccess } from 'vform'

Vue.component(Icon.name, Icon)
Vue.component(Child.name, Child)
Vue.component(Navigation.name, Navigation)
Vue.component(Sidebar.name, Sidebar)
Vue.component(HasError4.name, HasError4)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertSuccess.name, AlertSuccess)
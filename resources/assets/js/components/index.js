import Vue from 'vue'
import Icon from './Icon.vue'
import Child from './Child.vue'
import Navigation from './Navigation.vue';
import Sidebar from './Sidebar.vue';
import Navbuttons from './NavButtons.vue';
import Teamcreate from './modals/teams/create.vue';
import Fightcreate from './modals/fights/create.vue';
import { HasError4, AlertError, AlertSuccess } from 'vform'

Vue.component(Icon.name, Icon)
Vue.component(Child.name, Child)
Vue.component(Navigation.name, Navigation)
Vue.component(Navbuttons.name, Navbuttons)

Vue.component(Teamcreate.name, Teamcreate)
Vue.component(Fightcreate.name, Fightcreate)

Vue.component(Sidebar.name, Sidebar)
Vue.component(HasError4.name, HasError4)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertSuccess.name, AlertSuccess)

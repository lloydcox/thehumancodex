import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Bug from './bugs.vue'

Vue.use(VueRouter)
Vue.use(VueResource)

const router = new VueRouter({

    mode: 'history',
    routes:[
        { path: '/save_bug',component:Bug}
    ]
});

export default router
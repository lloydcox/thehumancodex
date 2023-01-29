import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Contact from './contact.vue'


Vue.use(VueRouter)
Vue.use(VueResource)

const router = new VueRouter({
    mode:'history',
    routes:[
        {path:'/send_message',component:Contact}
    ]
})

export default router


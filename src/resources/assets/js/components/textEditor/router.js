import Vue from 'vue';
import VueRouter from 'vue-router'
// import CKEditor from './textEditor.vue';
import VueTrix from 'vue-trix'
Vue.use(VueRouter);

// const router = new VueRouter({
//
//     mode: 'history',
//     routes:[
//         { path: '/save_bug',component:Bug}
//     ]
// })

// export default {
//     // ...
//     components: {
//         ckeditor: CKEditor.component
//     }
// }
// import Vue from 'vue'
//
// import Bug from './bugs.vue'



const router = new VueRouter({

    mode: 'history',
    routes:[
        { path: '/timeline',component:VueTrix}
    ]
});

export default router
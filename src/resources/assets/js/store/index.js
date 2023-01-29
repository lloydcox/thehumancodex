import Vuex from 'vuex'

import register from './register'
import connections from './connections'
import requests from './requests'
import timeline from './timeline'
import user from './user'

Vue.use(Vuex)

// injecting modules instead of state, actions and mutations
const index = new Vuex.Store({
  modules: {
    user,
    register,
    connections,
    requests,
    timeline
  }
})

export default index
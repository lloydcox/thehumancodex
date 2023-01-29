import state from './state'
import actions from './actions'
import mutations from './mutations'

const requests = {
  namespaced: true,
  state,
  actions,
  mutations
}

export default requests
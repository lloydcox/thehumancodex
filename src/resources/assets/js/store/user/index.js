import state from './state'
import actions from './actions'
import mutations from './mutations'

const user = {
  namespaced: true,
  state,
  actions,
  mutations
}

export default user
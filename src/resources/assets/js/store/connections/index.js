import state from './state'
import actions from './actions'
import mutations from './mutations'

const connections = {
  namespaced: true,
  state,
  actions,
  mutations
}

export default connections
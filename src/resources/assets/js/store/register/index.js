import state from './state'
import actions from './actions'
import mutations from './mutations'

const register = {
  namespaced: true,
  state,
  actions,
  mutations
}

export default register
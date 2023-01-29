import state from './state'
import actions from './actions'
import mutations from './mutations'

const timeline = {
  namespaced: true,
  state,
  actions,
  mutations
}

export default timeline
export default {
  ['setActiveItem'](state, connection) {
    if (state.activeItem === connection.id) {
      state.activeItem = null
      return
    }
    state.activeItem = connection.id
  },
  ['setConnections'](state, connections) {
    state.connections = connections
  }
}
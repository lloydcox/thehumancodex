import {_getConnections, _removeConnection} from '../../api/connections'

export default {
  async getConnections({state, commit}, getFor) {
    try {
      const response = await _getConnections(getFor)
      commit('setConnections', response.data)
      return response
    } catch (error) {
      throw error
    }
  },
  async removeConnection({state, commit}, connection) {
    try {
      const response = await _removeConnection(connection.id)
      commit('setConnections', response.data)
      return response
    } catch (error) {
      throw error
    }
  }
}
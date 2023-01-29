import {_getRequests, _approveRequest, _declineRequest} from '../../api/requests'

export default {
  async getRequests({state, commit}, getFor) {
    try {
      const response = await _getRequests(getFor)
      commit('setRequests', response.data)
      return response
    } catch (error) {
      throw error
    }
  },
  async approveRequest({state, commit}, request) {
    try {
      const response = await _approveRequest(request.id)
      commit('setRequests', response.data)
      return response
    } catch (error) {
      throw error
    }
  },
  async declineRequest({state, commit}, request) {
    try {
      const response = await _declineRequest(request.id)
      commit('setRequests', response.data)
      return response
    } catch (error) {
      throw error
    }
  }
}
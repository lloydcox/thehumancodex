import { _getUser } from '../../api/user'

export default {
  async getUser({commit}) {
    try {
      const response = await _getUser()
      commit('setUser', response.data)
      return response
    } catch (error) {
      throw error
    }
  }
}
import axios from 'axios'
import appConfig from '../config'

export async function _getRequests (userId, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/requests/${userId}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _approveRequest (userId, config = {}) {
  try {
    const response = await axios.post(`${appConfig.basePath}/requests/${userId}`,null, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _declineRequest (userId, config = {}) {
  try {
    const response = await axios.delete(`${appConfig.basePath}/requests/${userId}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}
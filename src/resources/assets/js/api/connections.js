import axios from 'axios'
import appConfig from '../config'

export async function _getConnectionsByConnectionCategories(connectionCategories, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/connections/categories/connections?categories=${connectionCategories}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}


export async function _getConnections (userId, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/connections/${userId}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _removeConnection (userId, config = {}) {
  try {
    const response = await axios.delete(`${appConfig.basePath}/connections/${userId}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _sendInvitation (emails, config = {}) {
  try {
    const response = await axios.post(`${appConfig.basePath}/connections`, emails, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _getConnectionCategories (userId, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/connections/categories/all/${userId}`, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _sendConnectionCategories (data, config = {}) {
  try {
    const response = await axios.post(`${appConfig.basePath}/connections/categories/send`, data, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}
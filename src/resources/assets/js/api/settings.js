import axios from 'axios'
import appConfig from '../config'

export async function _saveUserAvatar (image, config = {}) {
  try {
    const response = await axios.put(`${appConfig.basePath}/settings/avatar`, {image}, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _getProfile (credentials, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/settings/profile`, credentials, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _saveProfile (data, config = {}) {
  try {
    const response = await axios.put(`${appConfig.basePath}/settings/profile`, data, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _saveEmail (email, config = {}) {
  try {
    const response = await axios.put(`${appConfig.basePath}/settings/email`, {email}, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _savePassword (data, config = {}) {
  try {
    const response = await axios.put(`${appConfig.basePath}/settings/password`, data, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}
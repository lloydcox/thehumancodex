import axios from 'axios'
import appConfig from '../config'

export async function _getUser (credentials, config = {}) {
  try {
    const response = await axios.get(`${appConfig.basePath}/user`, credentials, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

import axios from 'axios'
import appConfig from '../config'

export async function _searchForPlaces (query, config = {}) {
  try {
    let data = {
      params: {
        query: query
      }
    }
    const response = await axios.get(`${appConfig.basePath}/search/places/`, {...data, ...config})
    return response.data
  } catch(error) {
    throw error.response.data
  }
}
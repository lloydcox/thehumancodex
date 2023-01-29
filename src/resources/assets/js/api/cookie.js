import axios from 'axios'
import appConfig from '../config'

export async function _acceptCookies (data, config = {}) {
    try {
        const response = await axios.post(`${appConfig.basePath}/cookies/login/accept`, data, config)
        return response.data
    } catch(error) {
        throw error.response.data
    }
}

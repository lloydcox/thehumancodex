import axios from 'axios'

export async function _login (credentials, config = {}) {
  try {
    const response = await axios.post(`/login`, credentials, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _registerValidator (step, userData, config = {}) {
  try {
    const response = await axios.post(`/register/${step}`, userData, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _register (userData, config = {}) {
  try {
    const response = await axios.post(`/register`, userData, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}

export async function _passwordReset (data, config = {}) {
  try {
    const response = await axios.post(`/passwords/email`, data, config)
    return response.data
  } catch(error) {
    throw error.response.data
  }
}


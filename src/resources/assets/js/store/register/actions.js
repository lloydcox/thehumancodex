import { _register } from '../../api/auth'

export default {
  async createAccount({state}) {
    return await _register({
      first_name: state.data.firstName,
      last_name: state.data.lastName,
      location: state.data.location,
      gender: state.data.gender,
      age: state.data.birthdate,
      email: state.data.email,
      password: state.data.password,
      connect_with: state.data.connectWith
    })
  }
}
export default {
  ['nextStep'](state) {
    state.step++
  },
  ['prevStep'](state) {
    state.step--
  },
  ['setEmail'](state, email) {
    state.data.email = email
  },
  ['setFirstName'](state, firstName) {
    state.data.firstName = firstName
  },
  ['setLastName'](state, lastName) {
    state.data.lastName = lastName
  },
  ['setGender'](state, gender) {
    state.data.gender = gender
  },
  ['setBirthdate'](state, birthdate) {
    state.data.birthdate = birthdate
  },
  ['setLocation'](state, location) {
    state.data.location = location
  },
  ['setPassword'](state, password) {
    state.data.password = password
  },
  ['setConnectWith'](state, connectWithId) {
    state.data.connectWith = parseInt(connectWithId)
  }
}
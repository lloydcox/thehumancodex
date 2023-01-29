<template>
  <div class="box-container is-narrow">
    <div class="box">
      <div class="box-progress-bar" :style="">
        <div class="progress" :style="'width:' + ((step+1) / 6) * 100 + '%'"></div>
      </div>
      <form :action="url" method="POST" @submit.prevent="null">
        <transition name="fade" mode="out-in">
          <register-box-email
              v-if="step === 0"
              key="email">
          </register-box-email>
          <register-box-name
              v-if="step === 1"
              key="name">
          </register-box-name>
          <register-box-gender
              v-if="step === 2"
              key="gender">
          </register-box-gender>
          <register-box-birth
              v-if="step === 3"
              key="birthdate">
          </register-box-birth>
          <register-box-location
              v-if="step === 4"
              key="location">
          </register-box-location>
          <register-box-password
              v-if="step === 5"
              key="password"></register-box-password>
        </transition>
      </form>
    </div>
  </div>
</template>

<script>
  import RegisterBoxEmail from './RegisterBoxEmail'
  import RegisterBoxName from './RegisterBoxName'
  import RegisterBoxGender from './RegisterBoxGender'
  import RegisterBoxBirth from './RegisterBoxBirth'
  import RegisterBoxLocation from './RegisterBoxLocation'
  import RegisterBoxPassword from './RegisterBoxPassword'

  export default {
    name: 'RegisterBox',
    components: {
      RegisterBoxEmail,
      RegisterBoxName,
      RegisterBoxGender,
      RegisterBoxBirth,
      RegisterBoxLocation,
      RegisterBoxPassword
    },
    props: {
      url: {
        required: true,
        type: String
      }
    },
    computed: {
      step() {
        return this.$store.state.register.step
      }
    },
    created() {
      // Save connect_with value
      const urlParams = new URLSearchParams(window.location.search)
      const ConnectWithId = urlParams.get('connect_with')
      this.$store.commit('register/setConnectWith', ConnectWithId)
    }
  }
</script>
<template>
  <div class="card profile-form">
    <div class="card-content" v-if="userData">
      <div class="profile-avatar image is-128x128" :class="{'is-loading': avatarLoading}">
        <img :src="avatar" alt="Avatar" class="avatar-img is-rounded has-cursor-pointer" @click="openImageInput">
      </div>
      <input type="file" class="is-hidden" accept="image/*" @change="onImageSelect" ref="imageInput">
      <form @submit.prevent="onSubmit">
        <thc-input label="My message to humanity" name="details" v-model="userData.description" :error="errors.description" multiline/>
        <thc-input label="First name" name="firstName" v-model="userData.first_name" :error="errors.first_name"/>
        <thc-input label="Last name" name="lastName" v-model="userData.last_name" :error="errors.last_name"/>
        <div class="columns">
          <div class="column">
            <div class="radio-circle">
              <input type="radio" name="gender" id="genderF" value="female" v-model="userData.gender">
              <label class="is-marginless" for="genderF">Female</label>
            </div>
          </div>
          <div class="column">
            <div class="radio-circle">
              <input type="radio" name="gender" id="genderM" value="male" v-model="userData.gender">
              <label class="is-marginless" for="genderM">Male</label>
            </div>
          </div>
          <div class="column">
            <div class="radio-circle">
              <input type="radio" name="gender" id="genderO" value="other" v-model="userData.gender">
              <label class="is-marginless" for="genderO">Other</label>
            </div>
          </div>
        </div>
        <thc-input label="Date of birth" name="age" v-model="userData.age" type="date"/>
        <div class="field">
          <div class="control is-expanded">
            <div class="select thc-select is-fullwidth">
              <label for="location">Location</label>
              <select title="location"
                      v-model="userData.location"
                      name="location"
                      id="location"
                      required>
                <option :value="country.name" v-for="country in countries">{{ country.name }}</option>
              </select>
            </div>
          </div>
        </div>
        <h4 class="text-center mt-5">About you</h4>
        <hr class="mt-2 mb-3" />
        <thc-input
                v-for="option in options"
                :label="option.title"
                :name="option.title"
                v-model="userData.categoryInputs[option.id]"
                type="text"
                :key="option.id"
        />
        <button type="submit" class="button is-primary is-rounded is-flat is-wide"
                :class="{'is-loading' : loading}"
                :disabled="loading">
          Save
        </button>
      </form>
    </div>
  </div>
</template>

<script>
  import { _saveUserAvatar, _getProfile, _saveProfile } from '../../api/settings'
  import countries from '../../resources/countries.json'
  import messages from '../../mixins/messages'

  export default {
    name: 'SettingsProfileForm',
    props: {
      postCategories: {
        type: Array,
        required:false
      },
    },
    mixins: [
      messages
    ],
    computed: {
      countries() {
        return countries
      },
      avatar() {
        return this.userData.avatar
      },
      options () {
        return this.postCategories
      },
    },
    data() {
      return {
        userData: {},
        errors: {},
        selectedFile: null,
        imageFormat: null,
        avatarLoading: false,
        loading: false
      }
    },
    created: async function () {
      let response = await _getProfile();
      this.userData = response.data;
      this.userData.categoryInputs = [];
      let tempCatArray = [];
      this.postCategories.forEach(function (category, index) {
         tempCatArray[category.id] = category.input !== null ?  category.input : '';
      });
      this.userData.categoryInputs = tempCatArray;
    },
    methods: {
      async onSubmit() {
        this.loading = true
        try {
          this.errors = {}
          let response = await _saveProfile(this.userData)
          this.displaySuccessMessage(response.message)
          this.loading = false
        } catch (error) {
          this.errors = error.data
          this.displayErrorMessage(error.message)
          this.loading = false
        }
      },
      openImageInput() {
        if(!this.avatarLoading) {
          this.$refs.imageInput.click()
        }
      },
      onImageSelect(event) {
        this.selectedFile = event.target.files[0] || null
        if (this.selectedFile) {
          this.avatarLoading = true
          this.codeImage()
            .then(this.saveAvatar)
            .catch((error) => {
              this.displayErrorMessage(error)
              this.avatarLoading = false
            })
        }
      },
      async saveAvatar(result) {
        let data = result.split(',')
        let base64 = data[1] || ''
        try {
          let response = await _saveUserAvatar(base64)
          this.userData.avatar = response.data
          this.displaySuccessMessage(response.message)
          this.avatarLoading = false
        } catch(error) {
          this.displayErrorMessage(error.message)
          this.avatarLoading = false
        }
      },
      codeImage() {
        return new Promise((resolve, reject) => {
          const reader = new FileReader()
          reader.readAsDataURL(this.selectedFile)
          reader.onload = () => resolve(reader.result)
          reader.onerror = error => reject(error)
        })
      }
    }
  }
</script>

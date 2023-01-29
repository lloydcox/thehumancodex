/**
 * Include dependencies.
 */

require('./bootstrap')




window.Vue = require('vue')

const VueTextareaAutosize = require('vue-textarea-autosize')
const flatPickr = require('vue-flatpickr-component')
const VueToastr2 = require('vue-toastr-2')
const Viewer = require('v-viewer')
const ModalDialogs = require('vue-modal-dialogs')
const VueClipboard = require('vue-clipboard2')

import 'vue-trix'
import Vue from 'vue'
import VueRouter from 'vue-router'
import BugRouter from './store/bugs/router.js'
import VeeValidate from 'vee-validate'
import VueResource from 'vue-resource'
// import TextRouter from './components/textEditor/router'
import moment from 'moment'
import { DropdownPlugin } from 'bootstrap-vue'


Vue.use(DropdownPlugin);
Vue.use(VueResource);
Vue.use(VeeValidate, {inject: 'false', fieldsBagName: 'veeFields', errorBagName: 'vErrors' });
Vue.use(VueRouter);

VueClipboard.config.autoSetContainer = true
Vue.use(VueClipboard)
Vue.use(VueTextareaAutosize)
Vue.use(flatPickr)
Vue.use(VueToastr2)
Vue.use(Viewer.default)
Vue.use(ModalDialogs)
Vue.use(require('vue-cookies'))
Vue.use(VueRouter)
/**
 * Register directives.
 */

require('./directives/loading')

/**
 * Register all root components.
 */

// Elements
Vue.component('thc-input', require('./elements/ThcInput.vue'))
Vue.component('thc-dropdown', require('./elements/ThcDropdown.vue'))
Vue.component('thc-copy-button', require('./elements/ThcCopyButton.vue'))
Vue.component('thc-button-component', require('./elements/ThcButtonComponent.vue'))


// Components
Vue.component('person-item', require('./components/share/PersonItem.vue'))
Vue.component('fixed-bottom-toolbar', require('./components/share/FixedBottomToolbar.vue'))

Vue.component('login-box', require('./components/auth/LoginBox.vue'))
Vue.component('register-box', require('./components/auth/register/RegisterBox.vue'))
Vue.component('forgot-password', require('./components/auth/ForgotPassword.vue'))

Vue.component('user-navbar-menu', require('./components/UserNavbarMenu.vue'))
Vue.component('timeline-form', require('./components/timeline/TimelineForm.vue'))
Vue.component('horizontal-timeline', require('./components/timeline/HorizontalTimeline.vue'))
Vue.component('new-requests', require('./components/requests/NewRequests.vue'))
Vue.component('cookie-banner', require('./components/cookie/CookieBanner.vue'))
Vue.component('connections', require('./components/connections/Connections.vue'))
Vue.component('connection-category-dropdown', require('./components/connections/ConnectionCategoryDropdown.vue'))
Vue.component('timeline', require('./components/timeline/Timeline.vue'))
Vue.component('timeline-map', require('./components/timeline/TimelineMap.vue'))
Vue.component('feed', require('./components/timeline/Feed.vue'))
Vue.component('first-post-form', require('./components/timeline/FirstPostForm.vue'))

Vue.component('invitations', require('./components/share/Invitations.vue'))

Vue.component('landing-page', require('./components/landing/LandingPage.vue'))

Vue.component('new-globe', require('./components/globe/NewGlobe.vue'))

Vue.component('search-results-users', require('./components/search/SearchResultsUsers.vue'))
Vue.component('search-results-posts', require('./components/search/SearchResultsPosts.vue'))
Vue.component('search-location', require('./components/search/SearchLocation.vue'))
Vue.component('search-form', require('./components/search/SearchForm.vue'))

Vue.component('settings-profile-form', require('./components/settings/SettingsProfileForm.vue'))
Vue.component('settings-email-form', require('./components/settings/SettingsEmailForm.vue'))
Vue.component('settings-password-form', require('./components/settings/SettingsPasswordForm.vue'))
Vue.component('settings-my-moments', require('./components/settings/SettingsMyMoments.vue'))
Vue.component('settings-my-comments', require('./components/settings/SettingsMyComments.vue'))
Vue.component('settings-my-kudos', require('./components/settings/SettingsMyKudos.vue'))
Vue.component('settings-my-connections', require('./components/settings/SettingsMyConnections'))

Vue.component('add-connection-button', require('./components/connections/AddConnectionButton.vue'))
Vue.component('remove-connection-button', require('./components/connections/RemoveConnectionButton.vue'))

const store = require('./store/index').default
Vue.component('app-bugs', require('./store/bugs/bugs.vue'));
Vue.component('app-textEditor',require('./components/textEditor/textEditor.vue'));
Vue.component('app-contact',require('./store/contact/contact.vue'));

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format("dddd, MMMM Do YYYY")
  }
});

const app = new Vue({
  el: '#app',
  router:BugRouter,
  store


});


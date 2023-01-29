<template>
  <div class="connections has-text-left spacer is-large">
    <slot name="top-section"></slot>
    <section v-for="(group, name) in usersInGroups" :data-group="name" :key="name">
      <h5 class="is-size-7 has-text-grey-light spacer" v-if="showGroups">{{ name }}</h5>
      <transition-group name="fade" mode="out-in">
        <person-item
            v-for="user in group"
            @clickOnPerson="openContact"
            :class="{'is-selected': user.isSelected}"
            :key="user.username"
            :user="user"
            :prevent-profile-redirection="true">
          <template slot="actions" v-if="!user.is_connected_with_me">
            <button v-if="user.id" class="button is-primary is-rounded is-flat is-small" @click="onSelect(user)">Connect</button>
            <span v-else class="has-text-weight-bold has-text-primary" @click="onSelect(user)">Send invitation to join</span>
          </template>
        </person-item>
      </transition-group>
    </section>
    <transition name="fade" mode="out-in">
      <fixed-bottom-toolbar v-if="selectedUsers.length">
        <form class="container has-text-centered" @submit.prevent="submit">
          <div class="spacer">
            <a href="#!" class="is-size-7 has-text-weight-bold" @click="selectAll">Select all</a>
          </div>
          <div class="spacer">
            <button class="button is-primary is-rounded is-flat is-small" :class="{'is-loading': isLoading}">Send invitation to join</button>
          </div>
          <div class="spacer">
            <button type="button" class="button is-rounded is-small is-circle" @click="selectNone">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </form>
      </fixed-bottom-toolbar>
    </transition>
  </div>
</template>

<script>
  import Vue from 'vue'
  import {_sendInvitation} from '../../api/connections'

  export default {
    name: 'Invitations',
    props: {
      users: {
        type: Array,
        required: true
      },
      showGroups: {
        type: Boolean,
        default: true
      },
      onSendRedirect: {
        type: String,
        required: false
      }
    },
    computed: {
      usersInGroups () {
        let users = _.sortBy(this.list, 'first_name')
        return _.groupBy(users, this.firstCharOfName)
      },
      selectedUsers () {
        return this.list.filter((user) => {
          return user.isSelected
        })
      }
    },
    data() {
      return {
        list: [],
        isLoading: false
      }
    },
    created () {
      this.list = this.users
    },
    methods: {
      firstCharOfName (user) {
        return user.first_name.slice(0, 1)
      },
      onSelect (user) {
        let index = _.findIndex(this.list, ['email', user.email])
        console.log(index)
        user.isSelected = !user.isSelected
        Vue.set(this.list, index, user)
      },
      openContact (user) {
        let win = window.open('codex/'+user.username, '_blank');
        win.focus();
      },
      selectAll () {
        this.toggleAll(true)
      },
      selectNone () {
        this.toggleAll(false)
      },
      toggleAll (status) {
        this.list.forEach((user, index) => {
          user.isSelected = status
          Vue.set(this.list, index, user)
        })
      },
      async submit () {
        this.isLoading = true
        let data = this.list.filter((user) => user.isSelected).map((user) => {
          return user.email
        })
        try {
          await _sendInvitation(data)
          if (this.onSendRedirect) {
            window.location.replace(this.onSendRedirect)
          } else {
            window.location.reload()
          }
        } catch (error) {
          this.isLoading = false
        }
      }
    }
  }
</script>

<style scoped>

</style>
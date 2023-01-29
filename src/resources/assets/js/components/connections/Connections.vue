<template>
  <div class="connections has-text-left spacer is-large">
    <h5 class="is-size-7 has-text-grey-light">Connect</h5>
    <section class="spacer">
      <a href="/search" class="add-connection is-pulled-left" v-if="allowAddingAndRemoving">
        <div class="dotted-circle">
          <i class="fas fa-plus"></i>
        </div>
        Add a connection
      </a>
      <a :href="'/codex/'+connection.username" class="image is-64x64 is-pulled-left mutual-connection" v-for="connection in favoriteConnections">
        <img :src="connection.avatar" class="is-rounded" :alt="connection.first_name">
      </a>
      <div class="is-pulled-right mutual-connection has-cursor-pointer" @click="showAllConnections = !showAllConnections">
        {{ showAllConnections ? 'Less' : 'See All' }}
      </div>
    </section>
    <template v-if="showAllConnections">
      <h5 class="is-size-7 has-text-grey-light">Connections 路 {{ connections.length }}</h5>
      <section v-for="(group, name) in connectionsInGroups" :data-group="name" :key="name">
        <h5 class="is-size-7 has-text-grey-light spacer">{{ name }}</h5>
        <transition-group name="fade" mode="out-in">
          <connection-item :connection="connection" v-for="connection in group" :key="connection.id" :allow-removing="allowAddingAndRemoving"></connection-item>
        </transition-group>
      </section>
    </template>
  </div>
</template>

<script>
  import ConnectionItem from './ConnectionItem'
  import {mapState, mapActions} from 'vuex'

  export default {
    name: 'Connections',
    components: {
      'connection-item': ConnectionItem
    },
    props: {
      userId: {
        type: String,
        required: true
      },
      allowAddingAndRemoving: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: mapState('connections', {
      connections: state => state.connections,

      favoriteConnections () {
        let limit = this.allowAddingAndRemoving ? 4 : 7
        return this.connections.slice(0, limit)
      },
      connectionsInGroups () {
        let connections = _.sortBy(this.connections, 'first_name')
        return _.groupBy(connections, this.firstCharOfName)
      }
    }),
    data () {
      return {
        showAllConnections: false
      }
    },
    created () {
      this.getConnections(this.userId)
    },
    methods: {
      ...mapActions('connections', [
        'getConnections'
      ]),
      firstCharOfName (user) {
        return user.first_name.slice(0, 1)
      }
    }
  }
</script>

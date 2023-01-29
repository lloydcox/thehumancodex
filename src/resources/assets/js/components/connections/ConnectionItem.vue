<template>
  <person-item :user="connection" :class="{'is-active': activeItem === connection.id}">
    <template slot="actions">
      <div class="connection-item-more" v-if="allowRemoving">
        <button class="button is-rounded is-danger is-small" v-if="activeItem === connection.id" @click="remove(connection)">Remove connection</button>
        <a class="icon" @click="setActiveItem(connection)">
          <i class="fas fa-ellipsis-v"></i>
        </a>
      </div>
    </template>
  </person-item>
</template>

<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
  import messages from '../../mixins/messages'

  export default {
    name: 'ConnectionItem',
    mixins: [
      messages
    ],
    props: {
      connection: {
        type: Object,
        required: true
      },
      allowRemoving: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: mapState('connections', {
      activeItem: state => state.activeItem,
    }),
    data () {
      return {

        showAllConnections: false
      }
    },
    methods: {
      ...mapActions('connections', [
        'removeConnection'
      ]),
      ...mapMutations('connections', [
        'setActiveItem'
      ]),
      async remove (connections) {
        try {
          const response = await this.removeConnection(connections)
          this.displaySuccessMessage(response.message || 'Connection was removed!')
        } catch (error) {
          this.displayErrorMessage(error || 'We can not remove this connection now')
        }
      }
    }
  }
</script>

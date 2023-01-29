<template>
  <div class="timeline">
    <div class="row mt-5 mb-5" v-if="selected_category !== null">
      <div class="col-md-12">
        <h1 class="h3 text-center">You are viewing posts of <br/> <b>{{selected_category}}</b></h1>
      </div>
    </div>

    <div class="row mt-2 mb-2">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <ul class="list-group list-group-horizontal justify-content-center">
              <li class="list-group-item m-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" v-model="connectionCategoryAll" @change="connectionCategoryAllSelected()">
                  <label class="form-check-label">
                    All
                  </label>
                </div>
              </li>
              <li class="list-group-item m-2" v-for="connectionCategory in connectionCategories" :key="connectionCategory.id">
                <div class="form-check">
                  <input class="form-check-input" :value="connectionCategory.id" type="checkbox" @change="connectionCategoryInputChanged()" v-model="selectedConnectionCategories">
                  <label class="form-check-label">
                    {{connectionCategory.title}}
                  </label>
                </div>
              </li>
            </ul>
          </div>
          <div class="card-footer text-center">
            <vue-bootstrap-typeahead
                    placeholder="Search your connections"
                    prepend="Connection Name:"
                    ref="connectionAutoComplete"
                    v-model="selectedConnection"
                    :serializer="item => item.first_name + ' ' + item.last_name"
                    :data="userConnections"
                    @hit="connectionSelected($event)"
            />
            <button class="btn btn-sm btn-primary ml-3">Mark All As Read</button>
          </div>
        </div>
      </div>
    </div>

    <timeline-post :post="post" v-if="post" v-for="post in posts" :key="posts.id"></timeline-post>
    <timeline-edit :post-categories="postCategories"></timeline-edit>
  </div>
</template>

<script>
  import TimelinePost from './TimelinePost'
  import TimelineEdit from './TimelineEdit'
  import messages from '../../mixins/messages'
  import { mapState, mapActions } from 'vuex'
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
  import {_getConnectionCategories, _getConnections} from '../../api/connections'
  import {_getConnectionsByConnectionCategories} from '../../api/connections'
  import {_filterPostsByConnections} from '../../api/timeline'


  export default {
    name: 'Timeline',
    props: {
      user_id:String,
      category_id: String,
      post_id: String,
      postCategories: {
        type: Array,
        default: () => [],
      },
      connectionCategories: {
        type: Array,
        default: () => [],
      }
    },
    mixins: [
      messages
    ],
    components: {
      TimelinePost,
      TimelineEdit,
      VueBootstrapTypeahead,
    },
    data(){
      return{
        selected_category :null,
        selectedConnectionCategories : [],
        userConnections: [],
        selectedConnection : null,
        connectionCategoryAll: true,
        selectedUser:null,
        loggedInUserId: null
      }
    },
    computed: {
      ...mapState('timeline', {
        posts: state => state.posts
      })
    },
    async created () {
      try {
        let postId = this.post_id ? this.post_id : undefined;
        let categoryId = this.category_id ? this.category_id : undefined;
        const userId =  this.user_id ? this.user_id :'feed';

        await this.getPosts({userId, postId,categoryId});

        this.posts.forEach((post)=>{
          if(this.category_id){
            this.selected_category = post.post_category.title;
          }
        });
      } catch (error) {
        console.log(error)
      }
    },
    methods: {
      ...mapActions('timeline', [
        'getPosts','markAllAs','sortPosts', 'sortPostsWithArray', 'changePostsState'
      ]),
      ...mapActions('user', [
        'getUser'
      ]),
      connectionCategoryAllSelected() {
         if(this.connectionCategoryAll){
            this.$refs.connectionAutoComplete.inputValue = ''
            this.selectedConnection = null;
            this.selectedConnectionCategories = [];
            this.getAllConnections();
         }
      },
      async connectionCategoryInputChanged() {
        this.selectedConnection = null;
        if(this.selectedConnectionCategories !== null && this.selectedConnectionCategories.length > 0){
          this.connectionCategoryAll = false;
          let param = this.selectedConnectionCategories.join(',');
          const response = await _getConnectionsByConnectionCategories(param);
          if (response.status === 'success' && response.data.length > 0) {
            this.$refs.connectionAutoComplete.inputValue = ''
            this.userConnections = null;
            this.userConnections = [];
            response.data.forEach((connection) => {
              this.userConnections.push(connection);
            });
            this.filterPosts();
          }else{
            this.userConnections = [];
            this.filterPosts();
          }
        }else{
          this.connectionCategoryAll = true;
          this.getAllConnections();
        }
      },
      async getAllConnections() {
        const user = await this.getUser();
        this.loggedInUserId = user.data.id;
        const response = await _getConnections(user.data.id);
        this.userConnections = [];
        if (response.status === 'success' && response.data !== null) {
          // response.data.forEach((connection) => {
          //   this.userConnections.push(connection);
          // });
          for (const connection of Object.entries(response.data)) {
            this.userConnections.push(connection[1]);
          };
          this.filterPosts();
        }else{
          this.userConnections = [];
        }
      },
      async filterPosts(){

        if(this.selectedConnection === null || this.selectedConnection === ''){
          let userIds = [];
          if(this.connectionCategoryAll){
            userIds.push(this.loggedInUserId);
          }
          this.userConnections.forEach((user) => {
            userIds.push(user.id);
          })
          let param = userIds.join(',');
          let response = await _filterPostsByConnections(param);
          let responsePosts = response.data;
          this.changePostsState(responsePosts);
        }else{
          let param = this.selectedUser.id.toString();
          let response = await _filterPostsByConnections(param);
          let responsePosts = response.data;
          this.changePostsState(responsePosts);
        }
      },
      connectionSelected(selected){
        this.selectedUser = selected;
        this.filterPosts();
      }
    },
    mounted() {
      this.getAllConnections();
    }
  }
</script>

<style scoped>

</style>

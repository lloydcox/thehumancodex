<template>
  <div class="timeline">

    <p v-if="posts.length" class="is-size-7">{{ user_name }}'s journey so far.</p>
    <!--<button type="submit" class="button is-primary is-rounded is-flat is-wide" @click="markAllAsRead" v-if="posts.length">-->
      <!--Mark all as read-->
    <!--</button>-->
    <timeline-post :post="post" v-if="post" v-for="post in posts" :key="post.id" :ref="post.unique_slug"></timeline-post>
    <timeline-edit></timeline-edit>
    <p v-if="posts.length" class="is-size-7">{{ user_name }}'s journey began.</p>
    <p v-if="!posts.length" class="is-size-6 has-text-grey has-text-left">There is nothing to display yet.</p>
  </div>


</template>

<script>
  import TimelinePost from './TimelinePost'
  import TimelineEdit from './TimelineEdit'
  import messages from '../../mixins/messages'
  import { mapState, mapActions } from 'vuex'

  export default {
    name: 'Timeline',
    mixins: [
      messages
    ],
    components: {
      TimelinePost,
      TimelineEdit
    },
    props: {
      userId: {
        type: String,
        required: true
      }
    },
    computed: mapState('timeline', {
      // posts:state=>state.posts,
      editedPost: state => state.editedPost
    }),

    data(){
      return{
        posts:'',
        user_data:'',
        user_name:''
      }
    },

    result:'',
    isReload :false,

    async created() {
      try {
        let user = await this.getUser();
        let name=this.$route.fullPath;
        var param=null;
        if (name.includes('codex')){
          param=name.replace('/codex/','').trim();
        }else if(name.includes('profile')){
          param=user.data.username;
        }
        this.posts=await this.getProfilePosts(param)
        if (this.posts.length === 0){
          this.user_name=user.data.first_name+' '+user.data.last_name;
          this.user_data={'username':user.data.username};
        }
        this.user_name=this.posts[0].user.first_name+' '+this.posts[0].user.last_name;
        this.user_data = this.posts[0].user;
        console.log(this.user_name);
      }catch (e) {
        console.log(e)
      }

    },
    async mounted () {
      this.post.location = this.user.location
      if (this.editedPost) {
        this.post = {...this.editedPost}
      }

      let hash = window.location.hash
      if (hash) {
        let el = this.$refs[hash.replace('#','')][0].$el
        window.scroll({
          top: el.offsetTop,
          left: 0,
          behavior: 'smooth'
        });
      }
    },

    methods: {
      ...mapActions('timeline', [
        'getPosts','getUserPosts','getProfilePosts','markAllAs'
      ]),
      ...mapActions('user', [
        'getUser'
      ]),
      async markAllAsRead(){
        try{
          const response=await this.markAllAs({'name':this.user_data.username});
          if(response.status==='success'){
            this.displaySuccessMessage(response.message || 'All posts are mark as viewed!');
          }
        } catch (error) {
          this.displayErrorMessage(error.message|| 'All posts are not mark as viewed!')
        }
      }
    }
  }
</script>

<style scoped>

</style>
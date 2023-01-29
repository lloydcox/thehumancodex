<template>
    <div id='timeline-embed' style="width: 100%; height: 600px"></div>
</template>

<script>
  import {_getPosts} from '../../api/timeline'
  import { mapActions } from 'vuex'

  export default {
    name: 'HorizontalTimeline',
    props: {

    },
    components: {

    },
    data(){
      return{
          timeline: null
      }
    },
    computed: {

    },
    created () {

    },
    methods: {
      ...mapActions('timeline', [
        'getPosts'
      ]),
      ...mapActions('user', [
        'getUser'
      ]),
      truncateString(str, num) {
        if (str.length <= num) {
          return str
        }
        return str.slice(0, num) + '...'
      },
    },
    async mounted() {
        const user = await this.getUser();
        const userId = user.data.id;
        let responsePosts = await this.getPosts({userId});
        console.log(responsePosts);
        let hTimelinePosts = [];

        if(responsePosts.length === 0){

            let hTimelinePost = {
              media:{
                  url:'',
                  thumbnail: ''
              },
              start_date: {
                  year:'',
                  month:'',
                  day:''
              },
              text: {
                  headline:'',
                  text:''
              }
            };

            let d = new Date();
            let date = d.getDate();
            let month = d.getMonth() + 1;
            let year = d.getFullYear();

            hTimelinePost.start_date.year = year.toString();
            hTimelinePost.start_date.month = month.toString();
            hTimelinePost.start_date.day = date.toString();
            hTimelinePost.text.headline = "NO POSTS TO SHOW";
            hTimelinePost.text.text = "Please add posts to see them in the timeline";

            hTimelinePosts.push(hTimelinePost);
        }else{
          responsePosts.forEach(post => {

            let hTimelinePost = {
              media:{
                  url:'',
                  thumbnail: ''
              },
              start_date: {
                  year:'',
                  month:'',
                  day:''
              },
              text: {
                  headline:'',
                  text:''
              }
            };

            if(post.date && post.date !== null && post.date !== undefined && post.date !== ""){
              let d = new Date(post.date);
              let date = d.getDate();
              let month = d.getMonth() + 1;
              let year = d.getFullYear();

              hTimelinePost.start_date.year = year.toString();
              hTimelinePost.start_date.month = month.toString();
              hTimelinePost.start_date.day = date.toString();
            }

            if(post.title && post.title !== null && post.title !== undefined && post.title !== ""){
              hTimelinePost.text.headline = post.title;
            }

            if(post.content && post.content !== null && post.content !== undefined && post.content !== ""){
              hTimelinePost.text.text = this.truncateString(post.content, 350);
            }

            if(post.youtube_url && post.youtube_url !== null && post.youtube_url !== undefined && post.youtube_url !== ""){
              hTimelinePost.media.url = post.youtube_url;
              hTimelinePost.media.thumbnail = post.youtube_url;
            }else if(post.image && post.image !== null && post.image !== undefined && post.image !== ""){
              hTimelinePost.media.url = post.image;
              hTimelinePost.media.thumbnail = post.image;
            }

            hTimelinePosts.push(hTimelinePost);
          });

        }

        console.log(user);
        console.log(hTimelinePosts);
        let hTimeline = {events:hTimelinePosts};
        let hTimeline_json = JSON.stringify(hTimeline);
        if(hTimelinePosts.length === 0){

        }
        this.timeline = new TL.Timeline('timeline-embed', hTimeline);



    }
  }
</script>

<style scoped>

</style>

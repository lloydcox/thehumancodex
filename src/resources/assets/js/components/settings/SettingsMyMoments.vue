<template>
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Access Your Moments</h5>
            <div v-if="posts.length!==0 ">
                <div class="row mt-4" v-for="(postList, index) in posts">
                    <div class="col-md-12 text-left">
                        <h1>{{ index| formatDate}}</h1>
                        <hr class="bg-secondary mt-2">
                        <div :id="'accordion_' + post.id" v-for="(post, postIndex) in postList">
                            <div class="card info-type-box">
                                <a @click="setActive(post.id)">
                                    <div class="row no-gutters py-2 px-2"
                                         v-bind:class="{'bg-primary text-white' : isActive(post.id), 'bg-light': !isActive(post.id) }">
                                        <div class="col-md-10 my-auto">
                                            <h5 class="h6 mb-1">{{post.title}}</h5>
                                            <h6 class="card-text text-small-para"><i class="fas fa-map-marker-alt mr-1"></i>{{post.location}}</h6>

                                        </div>
                                        <div class="col-md-2 my-auto text-right pr-2">
                                            <h6 class="card-text text-small-para">
                                                <i class="fas fa-thumbs-up mr-1"></i>{{post.kudos.length}} |
                                                <i class="fas fa-comments mr-1"></i>{{post.comments.length}}
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div :id="'collapse_' + post.id" class="collapse" v-bind:class="{'show' : isActive(post.id) }" aria-labelledby="headingOne"
                                 :data-parent="'#accordion_' + post.id">
                                <timeline-post :post="post" :is-comments="false "></timeline-post>
                                <timeline-edit :post-categories="postCategories"></timeline-edit>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row mt-1">
                    <div class="col-md-12 text-left">
                        <hr class="bg-secondary mt-2">
                        <h1>No Posts has been added yet!</h1>
                    </div>
                </div>
            </div>
        </section>
    </section>
</template>
<script>
    import TimelinePost from '../timeline/TimelinePost'
    import TimelineEdit from '../timeline/TimelineEdit'
    import VueBootstrapTypeahead from "vue-bootstrap-typeahead";
    import messages from "../../mixins/messages";
    import {mapActions, mapState} from "vuex";
    export default {
        name: 'SettingsMyMoments',
        props: {
            posts : {
                type: Object,
                default: () => {}
            },
            user_id:String,
            category_id: String,
            post_id: String,
            postCategories: {
                type: Array,
                default: () => [],
            }
        },
        mixins: [
            messages
        ],
        components: {
            TimelinePost,
            TimelineEdit
        },
        data() {
            return {
                activePost : null,
                connection_name:'',
                user_names:[],
                hit:'',
                selected_category: null
            }
        },
        async created() {
            let a=[];
            try {
                let postId = this.post_id ? this.post_id : undefined;
                let categoryId = this.category_id ? this.category_id : undefined;
                const userId =  this.user_id ? this.user_id :'feed';
                await this.getUser();
                await this.getPosts({userId, postId,categoryId});

                this.posts.forEach((post)=>{
                    if(this.category_id){
                        this.selected_category = post.post_category.title;
                    }
                    a.push(post.user.first_name+' '+post.user.last_name);
                });

                function onlyUnique(value, index, self) {
                    return self.indexOf(value) === index;
                }
                this.user_names = a.filter( onlyUnique );
            } catch (error) {
                console.log(error)
            }
        },
        methods: {
            setActive(postId){
                if(this.activePost === postId){
                    this.activePost = null;
                }else{
                    this.activePost = postId;
                }
            },
            isActive(postId){
                return postId === this.activePost
            },
            ...mapActions('timeline', [
                'getPosts','markAllAs','sortPosts'
            ]),
            ...mapActions('user', [
                'getUser'
            ]),
            sort(){
                this.sortPosts(this.connection_name.replace(/\s/g, '').trim().toLocaleLowerCase());
            },
            async markAllAsRead(){
                try {
                    const response=await this.markAllAs({'name':this.connection_name.replace(/\s/g, '').trim().toLocaleLowerCase()});
                    if(response.status==='success'){
                        this.displaySuccessMessage(response.message || 'All posts are mark as viewed!');
                    }
                }catch (e) {
                    this.displayErrorMessage(e.message|| 'All posts are not mark as viewed!');
                }
            }
        },
        mounted() {
            console.log(this.posts);
            console.log(this.postCategories);
        }
    }
</script>

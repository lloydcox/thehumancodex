<template>
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Access Your Kudos</h5>
            <div v-if="kudos.length!==0">
                <div class="row mt-4" v-for="(kudosList, index) in kudos">
                    <div class="col-md-12 text-left">
                        <h1>{{ index| formatDate}}</h1>
                        <hr class="bg-secondary mt-2">
                        <div :id="'accordion_' + kudosItem.id" v-for="(kudosItem, kudosIndex) in kudosList">
                            <div class="card info-type-box">
                                <a @click="setActive(kudosItem.id)">
                                    <div class="row no-gutters py-2 px-2"
                                         v-bind:class="{'bg-primary text-white' : isActive(kudosItem.id), 'bg-light': !isActive(kudosItem.id) }">
                                        <div class="col-md-12 my-auto">
                                            <h5 class="h6 mb-1">{{kudosItem.post.title}}</h5>
                                            <h5 class="h6 mb-1">{{kudosItem.content}}</h5>
                                            <h6 class="card-text text-small-para"><i class="fas fa-map-marker-alt mr-1"></i>{{kudosItem.post.location}}</h6>
                                            <h6 class="card-text text-small-para" v-if="kudosItem.user_id === kudosItem.post.user_id">Post by Me</h6>
                                            <h6 class="card-text text-small-para" v-else>Post by {{kudosItem.post.user.first_name}}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div :id="'collapse_' + kudosItem.id" class="collapse" v-bind:class="{'show' : isActive(kudosItem.id) }" aria-labelledby="headingOne"
                                 :data-parent="'#accordion_' + kudosItem.id">
                                <timeline-post :post="kudosItem.post" :is-comments="false"></timeline-post>
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
                        <h1>No kudos has been added yet!</h1>
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
        name: 'SettingsMyMoments', //TODO : Change this
        props: {
            kudos : {
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
            console.log(this.kudos);
            console.log(this.postCategories);
        }
    }
</script>

export default {
  ['setPosts'](state, posts) {
    state.posts = posts
  },
  ['sort'](state, username) {
    let user_posts=[]
    state.posts.forEach((post)=>{
      if (post.user.username === username){
        user_posts.push(post);
      }
    })
    // state.posts.forEach((post)=>{
    //   if (post.user.username !== username){
    //     user_posts.push(post);
    //   }
    // })
    state.posts=user_posts;

  },
  ['sortWithArray'](state, usernames){
    //console.log(usernames);
    let user_posts=[]
    state.posts.forEach((post)=>{
      usernames.forEach((username)=>{
        if (post.user.username === username){
          user_posts.push(post);
        }
      })
    })
    // state.posts.forEach((post)=>{
    //   if (user_posts.indexOf(post.user.username)){
    //     user_posts.push(post);
    //   }
    // })
    state.posts=user_posts;
  },
  ['changePostsState'](state, posts){
    state.posts = posts;
  },
  ['addPost'](state, post) {
    state.posts.unshift(post)
  },
  ['addComment'](state, {postId, comment}) {
    const index = _.findIndex(state.posts, (post) => {
      return post.id === postId
    })

    let post = state.posts[index]
    if(! post.comments) post.comments = []
    post.comments.push(comment)

    Vue.set(state.posts, index, post)
  },
  ['addKudos'](state, {postId, kudos}) {
    const index = _.findIndex(state.posts, (post) => {
      return post.id === postId
    })

    let post = state.posts[index]
    post.kudos.push(kudos)

    Vue.set(state.posts, index, post)
  },
  ['removeKudos'](state, {postId, userId}) {
    const postIndex = _.findIndex(state.posts, (post) => {
      return post.id === postId
    })

    let post = state.posts[postIndex]
    const kudosIndex = _.findIndex(post.kudos, (kudos) => {
      return kudos.user_id === userId
    })

    if (post.kudos.length > 1) {
      post.kudos = post.kudos.splice(kudosIndex, 1)
    } else {
      post.kudos = []
    }

    Vue.set(state.posts, postIndex, post)
  },
  ['deletePost'](state, postId) {
    const index = _.findIndex(state.posts, (post) => {
      return post.id === postId
    })
    state.posts.splice(index, 1)
  },
  ['markAllAs'](state, posts) {
    posts.forEach((post)=>{
      const ind=_.findIndex(state.posts,(p)=>{
        return p.id === post.id
      });
      state.posts.splice(ind, 1)
    })
  },
  ['markAs'](state, post) {
      const ind=_.findIndex(state.posts,(p)=>{
        return p.id === post.id
      });
      state.posts.splice(ind, 1)
  },
  ['replacePost'](state, {postId, post}) {
    const index = _.findIndex(state.posts, (p) => {
      return p.id === postId
    })

    Vue.set(state.posts, index, post)
  },
  ['openEditBox'](state, post) {
    state.editedPost = post
  },
  ['closeEditBox'](state) {
    state.editedPost = {}
  },
  ['hideOwn'](state, posts) {

    posts.forEach((post)=>{
      const ind=_.findIndex(state.posts,(p)=>{
        return p.id === post.id
      });
      state.posts.splice(ind, 1)
    })

  },
}
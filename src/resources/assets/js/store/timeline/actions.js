import { _getPosts, _getPostsInArea, _saveComment, _addKudos, _addToCodex, _deletePost, _savePost, _updatePost, _markAs,_getAPIFeed,_postBug, _getUserData,_postContactDetail,_getUsersPosts,_getProfilePosts, _markAllAs } from '../../api/timeline'

export default {
  async getPosts({state, commit}, {userId, limit, postId, categoryId}) {
    try {
      const response = await _getPosts({userId, limit, postId, categoryId})
      commit('setPosts', response.data)
      return response.data
    } catch (error) {
      throw error
    }
  },
  async sortPosts({state, commit}, username) {
    try {
      commit('sort',username);
      return true;
    } catch (error) {
      throw error
    }
  },
  async sortPostsWithArray({state, commit}, usernames) {
    try {
      commit('sortWithArray',usernames);
      return true;
    } catch (error) {
      throw error
    }
  },
  async changePostsState({state, commit}, posts) {
    try {
      commit('changePostsState', posts);
      return true;
    } catch (error) {
      throw error
    }
  },
  async getProfilePosts({state, commit},param){
    try {
      const response = await _getProfilePosts(param);
      commit('setPosts', response.data)
      return response.data
    }catch (error) {
      throw error
    }
  },
  async getUsersPosts({state, commit}, {userName, limit}) {
    try {
      const response = await _getUsersPosts({userName, limit})
      commit('setPosts', response.data)
      return response.data
    } catch (error) {
      throw error
    }
  },
  async getPostsInArea({state, commit}, {userId, bonds, limit}) {
    try {
      const response = await _getPostsInArea({userId, bonds, limit})
      commit('setPosts', response.data)
      return response
    } catch (error) {
      throw error
    }
  },
  async getPostsInAreaForUser({state, commit}, {userId, bonds, limit}) {
    try {
      const response = await _getPostsInArea({userId, bonds, limit})
      let notOwn=[];
      response.data.forEach((post)=>{
        if (post.isOwn === false){
          notOwn.push(post)
        }
      })
      console.log('action',notOwn)
      commit('setPosts',notOwn)
      return response
    } catch (error) {
      throw error
    }
  },
  async addPost ({state, commit}, postData) {
    try {
      const response = await _savePost(postData)
      const post = response.data
      commit('addPost', post)
      return response
    } catch (error) {
      throw error
    }

  },
  async addComment({state, commit}, {postId, commentData}) {
    try {
      const response = await _saveComment(postId, commentData)
      const comment = response.data
      commit('addComment', {postId, comment})
      return response
    } catch (error) {
      throw error
    }
  },
  async markAs({state, commit},post) {
    try {
      const response = await _markAs(post)
      commit('markAs', response.data)
      return response
    } catch (error) {
      throw error
    }
  },
  async markAllAs({state, commit},name) {
    try {
      const response = await _markAllAs(name)
      console.log(response.data);
      commit('markAllAs', response.data);
      return response
    } catch (error) {
      throw error
    }
  },
  async getAPIFeed({state, commit},postLocation){
    try {
      const response= await _getAPIFeed(postLocation);
      return response
    }catch (error) {
      throw error
    }
  },
  async getUserData({state, commit}){
    try {
      const response= await _getUserData();
      return response
    }catch (error) {
      throw error
    }
  },
  async postBug({state, commit},item){
    try {
      const response= await _postBug(item);
      return response;
    }catch (error) {
      throw error
    }
  },
  async postContactDetails({state, commit},contactDetails){
    try {
      const response= await _postContactDetail(contactDetails);
      return response;
    }catch (error) {
      throw error
    }
  },
  async addKudos({state, commit}, postId) {
    try {
      const response = await _addKudos(postId)
      const kudos = response.data
      if (!isNaN(kudos))
        commit('removeKudos', {postId, kudos})
      else
        commit('addKudos', {postId, kudos})
      return response
    } catch (error) {
      throw error
    }
  },
  async addToCodex({state, commit}, postId) {
    try {
      return await _addToCodex(postId)
    } catch (error) {
      throw error
    }
  },
  async deletePost({state, commit}, postId) {
    try {
      const response = await _deletePost(postId)
      commit('deletePost', postId)
      return response
    } catch (error) {
      throw error
    }
  },
  async updatePost({state, commit}, {postId, postData}) {
    try {
      const response = await _updatePost(postId, postData)
      const post = response.data
      commit('replacePost', {postId, post})
      return response
    } catch (error) {
      throw error
    }
  }
}

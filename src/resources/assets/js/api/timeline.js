import axios from "axios";
import appConfig from "../config";

export async function _savePost(postData, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/timeline`,
      postData,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getPosts(
  { userId, limit, postId, categoryId },
  config = {}
) {
  try {
    let url = `${appConfig.basePath}/timeline/${userId}`;
    if (postId) url += `?specially_requested_post=${postId}`;
    if (categoryId) url += `?specially_requested_category=${categoryId}`;
    if (limit && !postId) url += `?limit=${limit}`;
    const response = await axios.get(url, null, config);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _filterPostsByConnections(usernames, config = {}) {
  try {
    const response = await axios.get(
      `${appConfig.basePath}/timeline/filter/connection/posts?usernames=${usernames}`,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getProfilePosts(param, config = {}) {
  try {
    let url = `${appConfig.basePath}/profile/userData/${param}`;
    const response = await axios.get(url, null, config);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getUsersPosts({ username, limit }, config = {}) {
  try {
    let url = `${appConfig.basePath}/timeline/get_user_post/${username}`;
    if (limit) url += `?limit=${limit}`;
    const response = await axios.get(url, null, config);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getUserPosts(userId, config = {}) {
  try {
    const response = await axios.get(
      `${appConfig.basePath}/userPosts/${userId}`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getPostsInArea({ userId, bonds, limit }, config = {}) {
  try {
    let { ne, sw } = bonds;
    const response = await axios.get(
      `${appConfig.basePath}/timeline/${userId}?ne=${ne.lat},${ne.lng}&sw=${sw.lat},${sw.lng}&limit=${limit}`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getPostCategories(config = {}) {
  try {
    const response = await axios.get(
      `${appConfig.basePath}/post-categories`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _saveComment(postId, commentData, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/timeline/${postId}/comment`,
      commentData,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _addKudos(postId, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/timeline/${postId}/kudos`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _markAs(post, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/profile/mark_as_viewed`,
      post,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}
export async function _markAllAs(name, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/profile/mark_all_as_viewed`,
      name,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getAPIFeed(postLocation, config = {}) {
  try {
    const response = await axios.get(`/profile/apiFeed`, null, config);
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}
export async function _getUserData(postLocation, config = {}) {
  try {
    const response = await axios.get(
      `${appConfig.basePath}/profile/userData`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _postBug(item, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/save_bug`,
      item,
      config
    );
    return response;
  } catch (error) {
    throw error.response;
  }
}

export async function _postContactDetail(contactDetails, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/send_message`,
      contactDetails,
      config
    );
    return response;
  } catch (error) {
    throw error.response;
  }
}

export async function _addToCodex(postId, config = {}) {
  try {
    const response = await axios.post(
      `${appConfig.basePath}/timeline/${postId}/codex`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _deletePost(postId, config = {}) {
  try {
    const response = await axios.delete(
      `${appConfig.basePath}/timeline/${postId}`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _updatePost(postId, postData, config = {}) {
  try {
    const response = await axios.put(
      `${appConfig.basePath}/timeline/${postId}`,
      postData,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

export async function _getConnectionPosts(connectionId, config = {}) {
  try {
    const response = await axios.get(
      `${appConfig.basePath}/timeline/connection/posts/${connectionId}`,
      null,
      config
    );
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
}

<template>
  <timeline-form
    :user="user"
    @success="redirectToHome"
    v-if="user.id"
    :post-categories="postCategories"
  ></timeline-form>
</template>

<script>
import { _getUser } from "../../api/user";
import { _getPostCategories } from "../../api/timeline";

export default {
  name: "FirstPostForm",
  data() {
    return {
      user: {},
      postCategories: [],
    };
  },
  created() {
    this.getUserDetails();
    this.getCategories();
  },
  methods: {
    redirectToHome() {
      let location = window.location;
      let url = location.protocol + "//" + location.hostname;
      if (location.port) url += ":" + location.port;
      window.location.replace(url);
    },
    async getUserDetails() {
      try {
        let response = await _getUser();
        this.user = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getCategories() {
      try {
        let response = await _getPostCategories();
        this.postCategories = response.data;
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped></style>

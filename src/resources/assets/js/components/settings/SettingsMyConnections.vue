<template>
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Access Your Connections</h5>
            <div v-if="connections.length!==0">
                <div class="row mt-4" v-for="(connectionList, index) in connections">
                    <div class="col-md-12 text-left">
                        <h1><strong>Connected on: </strong>{{ index| formatDate}}</h1>
                        <hr class="bg-secondary mt-2">
                        <div class="card info-type-box" v-for="connection in connectionList">
                            <div class="row no-gutters py-2 px-2">
                                <div class="col-md-2 col-sm-12 my-auto text-center text-md-left p-2 p-md-3">
                                    <img :src="connection.avatar" alt="Avatar" class="is-rounded img-fluid">
                                </div>
                                <div class="col-md-8 col-sm-12 my-auto text-center text-md-left">
                                    <h6 class="h6 mb-1">
                                        {{connection.first_name + ' ' + connection.last_name}}
                                        <small>{{'(' + connection.username + ')'}}</small>
                                    </h6>
                                    <h6 class="card-text text-small-para"><i class="fas fa-map-marker-alt mr-1"></i>{{connection.location}}</h6>
                                    <h6 class="card-text text-small-para">
                                        <i class="fas fa-calendar-alt mr-1"></i>{{connection.age | formatDate}}
                                    </h6>
                                </div>
                                <div class="col-md-2 col-sm-12 my-auto text-center text-md-left mt-3 mt-md-auto">
                                    <a class="btn btn-primary btn-sm text-white" :href="'/codex/' + connection.username">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</template>
<script>
    export default {
        name: 'SettingsMyConnections',
        props: {
            connections: {
                type : Object,
                default: []
            }
        },
        mixins: [],
        components: {
        },
        data() {
            return {

            }
        },
        async created() {
        },
        methods: {

        },
        mounted() {
        }
    }
</script>

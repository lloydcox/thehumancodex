<template>
    <div class="alert alert-primary row nav-row" role="alert">
        By using our site you agree to our use of cookies to deliver a better site experience.
        <button v-on:click="accept" class="btn btn-primary mt-2 btn-sm">Agree</button><a :href="url"><button class="btn btn-danger mt-2 mx-2 btn-sm">Disagree</button></a>
    </div>
</template>

<script>
    import {_acceptCookies} from '../../api/cookie'

    export default {
        name: 'CookieBanner',
        components: {
        },
        props: {
            url: {
                type: String,
                require: true
            },
        },
        methods: {
            async accept() {
                let data = {};
                try {
                    let response = await _acceptCookies(data);
                    window.location.reload();
                    console.log(response.message);
                } catch (error) {
                    console.log(error.message);
                }
            }
        }
    }
</script>
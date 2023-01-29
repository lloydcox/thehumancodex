<template>
    <div class="container has-text-left">
        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9552598.562692897!2d-13.448257857142366!3d54.23004921683928!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x25a3b1142c791a9%3A0xc4f8a0433288257a!2sUnited+Kingdom!5e0!3m2!1sen!2slk!4v1554281502609!5m2!1sen!2slk" width="100%" height="500" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    <div class="col-md-5" id="contactArea">
                        <h4 id="topic"><strong>Get in Touch</strong></h4>
                        <br>
                        <form @submit.prevent="sendMessage" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                        v-validate="'required'"
                                        autofocus
                                       placeholder="Name"
                                       @keydown="onkeydown"
                                       v-model="contact_details.name"

                                >
                            </div>
                            <div class="form-group">
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       value=""
                                       placeholder="E-mail"
                                       @keydown="onkeydown"
                                       v-model="contact_details.email"
                                       v-validate="'required'"
                                >

                            </div>

                            <div class="form-group">
                                <textarea class="form-control"
                                          name="message" rows="3"
                                          placeholder="Message"
                                          @keydown="onkeydown"
                                          v-model="contact_details.message"
                                          v-validate="'required'"
                                >
                                </textarea>

                            </div>

                            <button class="button is-rounded is-primary col-5 float-right"
                                    type="submit" name="button"
                                    :disabled="isDisabledButton"
                            >
                                <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Submit
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import messages from '../../mixins/messages'
    export default {
        name: 'contact',
        mixins:[
            messages
        ],
        data(){
            return {
                contact_details: {
                    name:'',
                    email:'',
                    message:'',

                },
                isDisabledButton:true

            }
        },
        methods:{
            ...mapActions('timeline', [
                'postContactDetails'
            ]),
            async sendMessage(){
                try{
                    const response = await this.postContactDetails(this.contact_details);
                    this.onSuccess(response);
                }catch (error) {
                    this.onError(error)

                }
                this.resetFields();
            },
            onSuccess(response){
                this.displaySuccessMessage('Your message has been sent to administrator!');
                this.isDisabledButton = true;
            },
            onError(response){
                this.displayErrorMessage('Something went wrong! Could not send the email')
            },
            resetFields(){
                this.contact_details.name=''
                this.contact_details.email=''
                this.contact_details.message=''
            },
            onkeydown:function (event) {
                this.disableButton();
            },
            disableButton(){
                if(this.contact_details.name === '' && this.contact_details.email !== '' && this.contact_details.message === ''){
                    this.isDisabledButton=true;
                }
                if(this.contact_details.name !== '' && this.contact_details.email !== '' && this.contact_details.message === ''){
                    this.isDisabledButton=true;
                }
                if(this.contact_details.name !== '' && this.contact_details.email === '' && this.contact_details.message !== ''){
                    this.isDisabledButton=true;
                }
                if(this.contact_details.name === '' && this.contact_details.email !== '' && this.contact_details.message !== ''){
                    this.isDisabledButton=true;
                }
                if(this.contact_details.name !== '' && this.contact_details.email !== '' && this.contact_details.message !== '') {
                    this.isDisabledButton=false;
                }
                if(this.contact_details.name === '' && this.contact_details.email === '' && this.contact_details.message === ''){
                    this.isDisabledButton=true;
                }
            },
        },

    }
</script>


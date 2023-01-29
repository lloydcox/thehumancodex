<template>
    <div class="row" style="margin: 0">
        <div class="col-xl-2 col-lg-2 col-md-1 col-sm-1 col-1"></div>
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 col-10">
            <div class=" jumbotron jumbotron-fluid shadow" style="background-color: white; border-radius: 20px">
                <div class="p-6">
                    <!--bug report form-->
                    <form @submit.prevent="submitBug" method="post" enctype="multipart/form-data">
                        <!--title-->
                        <div class="form-group row text-center-modified">
                            <label class="col-sm-3 col-form-label">Title<span class="required">
                                &nbsp;*</span></label>
                            <div class="col-sm-9">
                                <label for="inputTitle"></label>
                                <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Title"
                                        name="title"
                                        v-validate="'required'"
                                        v-model="item.title"
                                        id="inputTitle"
                                        @keyup="onkeyup"
                                        style="white-space: pre"
                                        autofocus>
                            </div>
                        </div>
                        <!--description-->
                        <div class="form-group row text-center-modified">
                            <label class="col-sm-3 col-form-label">Description<span
                                    class="required">&nbsp;*</span></label>
                            <div class="col-sm-9">
                                <textarea
                                        type="text"
                                        name="description"
                                        id="inputDescription"
                                        v-validate="'required'"
                                        v-model="item.description"
                                        @keyup="onkeyup"
                                        rows="5"
                                        class="textarea"
                                        style="white-space: pre"
                                        label="Description">
                        </textarea>
                            </div>
                        </div>

                        <!--Attachments-->
                        <div class="form-group row text-center-modified">
                            <label class="col-sm-3 col-form-label">Attachment</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input"
                                           name="Image"
                                           @change="getImages($event)"
                                           accept="image/x-png,image/gif,image/jpeg"/>
                                    <label class="custom-file-label">Choose file...</label>
                                    <div v-show="errors.has('Image1')" class="help-block alert alert-danger">
                                        {{ errors.first('Image') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9" style="display:inline-block;">
                                <div v-for="i in indexes" v-show="item.images[i]"
                                     class="card border-info shadow text-info p-1 my-card"
                                     style="display:inline-block;margin-right:5px;">
                            <span class="col-sm-auto" aria-hidden="true">
                                Attachment {{ i+1 }}<i
                                    class="fas fa-times p-1 icon-attachment"
                                    v-on:click="remove_image(i)"></i>
                            </span>
                                </div>
                            </div>
                        </div>

                        <!--Button Sets-->
                        <div class="form-group row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button v-on:click="resetData()"
                                        type="button"
                                        class="button is-rounded col-5 btn-reset is-wide float-right m-1"
                                        :disabled="isDisabledButton">Reset
                                </button>
                                <button type="submit"
                                        class="button is-rounded is-primary col-5 btn-block float-right m-1"
                                        :disabled="isDisabledButton">Submit
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-1 col-sm-1 col-1"></div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import messages from '../../mixins/messages'

    export default {
        name: "bugs",
        mixins: [
            messages
        ],
        mounted() {
            console.log('mounted');
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('data-require', 'jquery')
            recaptchaScript.setAttribute('data-semver', '3.1.1')
            recaptchaScript.setAttribute('src', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js')
            document.head.appendChild(recaptchaScript)
        },
        data() {
            return {
                item: {
                    title: '',
                    description: '',
                    images: []
                },
                isDisabledButton: true,
                indexes: [0, 1, 2]
            }
        },
        methods: {
            ...mapActions('timeline', [
                'postBug'
            ]),
            async submitBug() {
                const response = await this.postBug(this.item);
                try {
                    this.onSuccess(response);
                } catch (error) {
                    this.onError(error);
                }
                this.resetData();
                this.disableButton();
            },
            onSuccess(response) {
                this.displaySuccessMessage(response.data.message || 'BugReport was added!');
            },
            onError(error) {
                this.displayErrorMessage(error.message || 'We can not add this BugReport!')
            },
            onkeyup: function (event) {
                this.disableButton();
            },
            disableButton() {
                if (this.item.title === '' && this.item.description !== '') {
                    this.isDisabledButton = true;
                } else if (this.item.title !== '' && this.item.description === '') {
                    this.isDisabledButton = true;
                } else if (this.item.title !== '' && this.item.description !== '') {
                    this.isDisabledButton = false;
                } else if (this.item.title === '' && this.item.description === '') {
                    this.isDisabledButton = true;
                }
            },
            getImages(e) {
                let isFound = false;
                let isDupliate = false;
                if (this.item.images.length > 2) {
                    for (let index = 0; index < this.item.images.length; index++) {
                        if (this.item.images[index] === null) {
                            isFound = true;
                            const fileReader = new FileReader();
                            fileReader.readAsDataURL(e.target.files[0]);
                            fileReader.onload = (e) => {
                                console.log("Add Again");
                                for (let i = 0; i < this.item.images.length; i++) {
                                    if (this.item.images[i] === e.target.result) {
                                        this.displayErrorMessage("You Have Already Added This Attachment.\nPlease Select Another Attachment");
                                        isDupliate = true;
                                        break;
                                    }
                                }
                                if (!isDupliate) {
                                    console.log("Add Again Duplicate");
                                    this.item.images[index] = e.target.result;
                                    this.indexes.splice(index, 0, index);
                                    isDupliate = false;
                                }
                            };
                            break;
                        }
                    }
                    if (!isFound) {
                        this.displayErrorMessage('Sorry,You have Reached Maximum Attachment Count.\nMaximum Attachment Count is 3.');
                    }
                } else {
                    const fileReader = new FileReader();
                    fileReader.readAsDataURL(e.target.files[0]);
                    fileReader.onload = (e) => {
                        for (let i = 0; i < this.item.images.length; i++) {
                            if (this.item.images[i] === e.target.result) {
                                this.displayErrorMessage("You Have Already Added This Attachment.\nPlease Select Another Attachment");
                                isDupliate = true;
                                break;
                            }
                        }
                        if (!isDupliate) {
                            this.item.images.push(e.target.result);
                            isDupliate = false;
                        }
                    };
                }
            },
            resetData() {
                this.item.title = "";
                this.item.description = "";
                this.item.images.shift();
                const index = this.item.images.indexOf(this.item.images[1]);
                this.item.images.splice(index, 1);
                this.item.images.pop();
                this.disableButton();
            },
            remove_image(index) {
                delete this.item.images[index];
                this.indexes.splice(index, 1);
                this.setIndexNull(index);
            },
            setIndexNull(index) {
                return this.item.images[index] = null;
            }
        }
    }

</script>

<style scoped>
    body {
        position: relative;
        display: block;
        overflow: auto;
    }

    #myInput {
        display: none;
    }

    .www {
        border: 2px solid black;
    }

    .attach-lable {
        border: 2px solid gray;
        border-radius: 10px;
    }

    .icon-attachment {
        color: rgba(187, 0, 0, 0.75);
        float: right;
    }

    .btn-location {
        margin-left: 60%;
    }

    .btn-reset {
        background-color: gray;
        color: white;
    }

    .btn-reset:hover {
        color: white;
    }

    .attachment-container {
        display: grid;
    }

    .attachment-container--fill {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }

    .required {
        color: red;
    }
</style>

<template>
    
    <div >
        <b-dropdown id="dropdown-1" text="Connection Group" class="mb-md-2 select is-multiple">
            <div class="checkbox">
                <label>
                    <input type="checkbox" checked disabled>Connection
                </label>
                <br>
                <div v-for="connectionCategory in connectionCategories" :key="connectionCategory.title">
                    <label>
                        <input type="checkbox" :checked="connectionCategory.checked" :value="connectionCategory.id" class="connectionCategoryCheck" name="connectionCategoryCheck">{{connectionCategory.title}}
                    </label>
                    <br>
                </div>
            </div>
            <b-dropdown-divider></b-dropdown-divider>
            <button @click="onSubmitCategories()">Select</button>
        </b-dropdown>
    </div>

</template>

<style>
    .dropdown-menu .checkbox {
        width: 100%;
    }
    .checkbox label{
        padding: 5px 0px 5px 15px;
        background: #ffffff;
        width: 100%;
        margin: 0px 0 0 0;
        border-bottom: 1px solid #d2d2d2;
    }
    ul button{
        width: 100%;
        border-radius: 4px;
        background-color: #0683dc;
        color: #fff;
    }
    .checkbox label input{
        margin: 0px 5px 0 0;
    }
    #dropdown-1 ul{
        z-index: 1;
    }
</style>>

<script>
    import {_getConnectionCategories, _sendConnectionCategories} from '../../api/connections'
    import messages from '../../mixins/messages'

  export default {
    name: 'ConnectionCategoryDropdown',
    mixins: [
    messages
    ],
    components: {
        
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data () {
      return {
        connectionCategories: null,
        connectionGroupings: null,
        selectedConnectionCategories: [],
      }
    },
    created () {
        this.getConnectionCategories();
        
    },
    methods: {

            async getConnectionCategories(){
                try {
                const response = await _getConnectionCategories(this.user.id)
                if(response.status==='success'){
                    this.connectionCategories = response.data.connectionCategories;
                }
                } catch (error) {
                    console.log(error);
                }
            },
            async sendConnectionCategories(data){
                try {
                const response = await _sendConnectionCategories(data)
                if(response.status==='success'){
                    console.log(response.message);
                    this.displaySuccessMessage(response.message || 'Connection Categories Updated!');
                }
                } catch (error) {
                    console.log(error);
                    this.displayErrorMessage(error.message|| 'Connection Categories Not Updated!')
                }
            },
            onSubmitCategories(){
                this.selectedConnectionCategories = [];
                const self = this;
                $('.connectionCategoryCheck').each(function(){
                    if ($(this).is(":checked")) {
                        self.selectedConnectionCategories.push($(this).val());
                    }
                });
                let implodedSelectedCategories = this.selectedConnectionCategories.join(", ");
                this.sendConnectionCategories({
                    connectedUser: this.user.id,
                    selectedConnectionCategories: implodedSelectedCategories,
                });
            },
        
    },
    mounted(){
        console.log(this.user);
    }
  }
</script>


<template>
    <div id="app">
        <!--<ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>-->
        <VueTrix v-model="editorContent"  />
        <!--<input type="text" :disabled="validated == 1">-->
    </div>
</template>

<script>
    import ClassicEditor from './textEditor';

    export default {
        name: 'textEditor',
        data() {
            return {
                editor: ClassicEditor,
                editorData: '<p>Rich-text editor content.</p>',
                editorConfig: {
                    // The configuration of the rich-text editor.
                }
            };
        }
    }
</script>
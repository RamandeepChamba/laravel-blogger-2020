<template>
    <div class="container row">
        <button class="btn btn-success" 
            v-show="!hasForm"
            v-on:click="toggleForm">
            Reply
        </button>
        <component :is="replyForm"
            class="my-3 ml-3"
            :blog-id="blogId"
            :parent-id="parentId"
            v-on:toggle-reply-form="toggleForm"
            v-on:reply-added="replyAdded($event); toggleForm($event);">
        </component>
    </div>
</template>

<script>
    import Form from './Form'

    export default {
        props: ['blogId','parentId', 'authId'],
        components: {
            'reply-form': Form
        },

        data() {
            return {
                replyForm: null,
                hasForm: false
            }
        },

        methods: {
            toggleForm: function () {

                if(this.$props.authId === -1) {
                    window.location = '/login'
                    return
                }

                if (this.hasForm) {
                    this.disableForm()
                }
                else {
                    this.renderForm()
                }
            },
            renderForm: function () {
                this.replyForm = 'reply-form'
                this.hasForm = true
            },
            disableForm: function () {
                this.replyForm = null
                this.hasForm = false
            },
            replyAdded: function(id) {
                this.$emit('reply-added', id)
            }
        }
    }
</script>

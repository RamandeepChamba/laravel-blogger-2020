<template>
    <div class="container row">
        <button class="btn btn-success" 
            v-show="!hasForm"
            v-on:click="toggleForm">
            Reply
        </button>
        <component :is="replyForm" 
            :hasForm="hasForm" 
            :toggleForm="toggleForm"
            :blog-id="blogId"
            :parent-id="parentId">
        </component>
    </div>
</template>

<script>
    import Form from './Form'

    export default {
        props: ['blogId','parentId'],
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
            }
        }
    }
</script>

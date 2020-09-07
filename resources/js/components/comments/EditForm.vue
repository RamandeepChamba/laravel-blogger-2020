<template>
    <div class="container my-3">
        <form>
            <textarea name="comment" cols="30" rows="2"
                aria-describedby="commentHelp"
                maxlength="300"
                minlength="5"
                v-model="commentText"
                ref="editTextarea"
                required>
            </textarea>
            <small id="commentHelp" class="form-text text-muted">
                Can only use upto 300 characters
            </small>
            <br>
            <button type="button" 
                class="btn btn-success"
                :disabled="processing"
                v-on:click="updateComment">
                {{processing ? 'Saving...' : 'Save'}}
            </button>
            <button type="button" class="btn btn-warning"
                :disabled="processing"
                v-on:click="$emit('remove-edit-form')">
                Cancel
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['comment', 'processing'],
        
        data() {
            return {
                commentText: this.$props.comment.comment
            }
        }, 
        mounted() {
            this.focusInput()
        },
        methods: {
            focusInput() {
                this.$refs.editTextarea.focus()
            },
            updateComment() {
                this.$emit('update-comment', this.commentText)
            },
        },
    }
</script>
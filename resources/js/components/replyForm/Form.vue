<template>
    <div class="container my-3">
        <form action="/comments" method="POST">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="blog_id" :value="blogId">
            <input type="hidden" name="parent_id" :value="parentId">
            <textarea name="comment" cols="30" rows="2"
                aria-describedby="commentHelp"
                placeholder="Enter reply to above comment"
                maxlength="300"
                minlength="5"
                required
            ></textarea>
            <small id="replyHelp" class="form-text text-muted">
                Can only use upto 300 characters
            </small>
            <br>
            <button type="submit" class="btn btn-success">Reply</button>
            <button type="button" class="btn btn-warning"
                v-on:click="toggleForm">
                Cancel
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['hasForm', 'toggleForm', 'blogId', 'parentId'],
        
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
    }
</script>

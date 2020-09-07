<template>
    <div class="row">
        <h3>Comments {{commentsCount}}</h3>
    </div>
</template>

<script>  
    export default {
        props: ['commentsCount'],

        data() {
            return {
            }
        },
    }
</script>

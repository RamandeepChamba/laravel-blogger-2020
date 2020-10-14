<template>
    <div class="container" v-if="!hide">
        <div :class="`alert fade show alert-${flashClass}`" ref="alert">
            {{flashMessage}}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['flashMessage', 'flashClass'],
        data() {
            return {
                hide: false
            } 
        },
        mounted() {
            setTimeout(this.removeAfter, 5000);
        },
        methods: {
            removeAfter() {
                $(this.$refs['alert']).alert('close')
                this.hide = true
            }
        }
    }
</script>

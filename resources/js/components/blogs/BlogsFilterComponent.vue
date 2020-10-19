<template>
    <div>
        <button class="btn" 
            :class="hasFilters ? 'btn-success' : 'btn-primary'" 
            type="button" 
            v-show="!filtering" 
            @click="showFilterForm" 
            :disabled="processing">
            {{hasFilters ? 'Filtered result' : 'Filter'}}
        </button>

        <form action="/blogs" v-show="filtering" method="GET">
            <div class="form-group">
                <label for="sortBy">Sort By</label>
                <select class="form-control" name="sortBy" id="sortBy">
                    <option value="latest">Latest</option>
                    <option value="popular">Popular</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <div class="form-group form-check" v-show="(authId !== -1)">
                <input class="form-check-input" type="checkbox" 
                    name="followingsOnly" id="followingsOnly" :checked="blogFilters.followingsOnly">
                <label class="form-check-label" for="followingsOnly">
                    Followings only
                </label>
            </div>
            <a href="/blogs">
                <button class="btn btn-warning" type="button">
                    Reset
                </button>
            </a>
            <button class="btn btn-danger" type="button" 
                @click="cancelFilterForm" 
                :disabled="processing">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['authId', 'blogFilters'],
        data() {
            return {
                processing: false,
                filtering: false,
                hasFilters: this.$props.blogFilters.sortBy 
                    || this.$props.blogFilters.followingsOnly,
            }
        },
        mounted() {
            if (this.$props.blogFilters.sortBy) {
                document.getElementById('sortBy').value = this.$props.blogFilters.sortBy
            }
        },
        methods: {
            showFilterForm() {
                if(this.processing) {
                    return
                }
                this.processing = true
                this.filtering = true
                this.processing = false
            },
            cancelFilterForm() {
                if(this.processing) {
                    return
                }
                this.processing = true
                this.filtering = false
                this.processing = false
            }
        }
    }
</script>

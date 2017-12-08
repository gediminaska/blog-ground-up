<style scoped>
    .slug-widget{
        display:flex;
        justify-content: flex-start;
        align-items: center;
    }
    .wrapper{
        margin-left: 8px;
    }
    .slug {
        background-color: #fdfd96;
        padding: 3px 5px;
    }
</style>

<template>
    <div class="slug-widget">
        <div class="icon-wrapper wrapper">
            <i class="fas fa-link"></i>
        </div>

        <div class="url-wrapper wrapper">
            <span class="root-url">{{url}}</span
            ><span class="subdirectory-url">{{subdirectory}}/</span
            ><span class="slug" v-show="slug">{{slug}}</span>
        </div>

        <div class="button-wrapper wrapper">
            <button class="button is-small">Edit</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            url: {
                type: String,
                required: true
            },
            subdirectory: {
                type: String,
                required: true
            },
            title: {
                type: String,
                required: true
            }
        },
        data: function() {
            return {
                slug: this.convertTitle()
            }
        },
        methods: {
            convertTitle: function(){
                return Slug(this.title)
            }
        },
        watch: {
            title: function() {
               this.slug = this.convertTitle();

            }
            slug: function(val) {
                this.$emit('slug-changed', val)
            }
        }
    }
</script>

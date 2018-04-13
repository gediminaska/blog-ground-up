<template>
</template>
<script>
    export default {
        props: {
            tag: {
                type: String,
                required: true
            }
        },
        data: function() {
            return {
                tagSlug: this.setSlug(this.tag),
                api_token: this.$root.api_token,
            }
        },
        methods: {
            setSlug: function(newVal, count = 0) {
                // Slugify new val
                let slug = Slug(newVal + (count > 0 ? `-${count}` : ''));
                let vm = this;
                vm.tagSlug = slug;
                vm.$emit('tag-slug-changed', slug);
            }
        },
        watch: {
            tag: _.debounce(function() {
                this.setSlug(this.tag);
            }, 500)
        }
    }
</script>

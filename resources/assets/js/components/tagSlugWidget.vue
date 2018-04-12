<template>

</template>
<script>
    export default {
        props: {
            title: {
                type: String,
                required: true
            }
        },
        data: function() {
            return {
                slug: this.setSlug(this.title),
                wasEdited: false,
                api_token: this.$root.api_token,
            }
        },
        methods: {
            setSlug: function(newVal, count = 0) {
                // Slugify new val
                let slug = Slug(newVal + (count > 0 ? `-${count}` : ''));
                let vm = this;
                vm.slug = slug;
                vm.$emit('slug-changed', slug);

            }
        },
        watch: {
            title: _.debounce(function() {
                this.setSlug(this.title);
            }, 500)
        }
    }
</script>

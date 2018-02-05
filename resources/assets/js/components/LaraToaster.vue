<template>
  <div></div>
</template>
<script>
  export default {
    props: ['message','type'],
    mounted() {
      this.$toast.open({
        message: this.message,
        type: this.type,
        duration: 4000,
        queue: true,
      })
    }
  }
</script>

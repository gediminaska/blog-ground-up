<template>
    <div>
        <input type="hidden" id="trix" :name="name" :value="value">
        <trix-editor input="trix" :value="value">Horray</trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix';
    export default {
        props: ['name', 'value']
    }
</script>
<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{label}}</label>
        <slot></slot>
        <div :id="help" class="form-text text-muted">{{msgHelp}}</div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'label', 'help', 'msgHelp']
    }
</script>

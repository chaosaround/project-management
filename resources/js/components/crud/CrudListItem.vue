<template>
    <div class="list-group-item d-flex justify-content-md-between align-items-center">
        <div class="d-flex align-items-center">
            <span v-if="draggable" class="draganddrop-icon"></span>
            <strong v-if="item.priority">#{{index+1}}&nbsp;</strong>
            {{ item.name }}
            &nbsp;
            <span v-if="item.tag" class="badge badge-dark">{{ item.tag }}</span>
        </div>
        <div>
            <button type="button" class="btn btn-info btn-sm" @click="$emit('edit')">Edit</button>
            <button type="button" class="btn btn-danger btn-sm" @click="$emit('delete')">Delete</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: Object,
            draggable: Boolean,
            index: Number
        }
    }
</script>

<style scoped>
    .draganddrop-icon {
        display: inline-block;
        border-top: 2px solid #ccc;
        width: 25px;
        padding-top: 2px;
        cursor: grab;
        margin: 2px 10px 0 0;
    }

    .draganddrop-icon:before,
    .draganddrop-icon:after {
        display: block;
        content: "";
        border-top: 2px solid #ccc;
        padding-top: 2px;
    }
</style>
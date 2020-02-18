<template>
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-md-between align-items-center">
            <div>
                <strong>{{ title }}</strong>
                &nbsp;&nbsp;
                <button type="button" class="btn btn-success btn-sm" @click="$emit('add')">Add new</button>
            </div>
            <div>
                <slot name="filter"/>
            </div>
        </div>
        <div class="card-body">
            <draggable v-if="!empty" :disabled="!draggable" v-model="list" class="list-group" @update="onUpdate">
                <crud-list-item v-for="(item, index) in list" :item="item" :index="index"
                                v-bind:key="item.id"
                                :draggable="draggable"
                                @edit="$emit('edit', item)"
                                @delete="$emit('delete', item)"/>
            </draggable>
            <span v-else>Nothing here yet...</span>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'
    import CrudListItem from './CrudListItem'

    export default {
        props: {
            draggable: Boolean,
            title: String,
            data: Array
        },
        components: {
            draggable,
            CrudListItem
        },
        computed: {
            list: {
                get() {
                    return this.data
                },
                set(data) {
                    this.$emit('list-dragged', data)
                }
            },
            empty() {
                return this.data === undefined || this.data.length === 0
            }
        },
        methods: {
            onUpdate(e) {
                if (e.newIndex !== undefined) {
                    this.$emit('item-dragged', e.newIndex)
                }
            }
        }
    }
</script>
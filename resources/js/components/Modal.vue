<template>
    <div v-if="showModal">
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ title }}</h5>
                                <button type="button" class="close" @click="$emit('modal-close')">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <slot/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="$emit('modal-close')">
                                    Close
                                </button>
                                <button type="button" class="btn" v-bind:class="'btn-' + okButtonType"
                                        @click="$emit('ok')">{{ okButtonText }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            showModal: Boolean,
            okButtonText: String,
            okButtonType: {
                type: String,
                default: 'primary'
            }
        }
    }
</script>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>

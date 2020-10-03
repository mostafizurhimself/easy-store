<template>
    <div class="text-white notifications-panel">
        <div class="border-b border-80">
            <div class="text-center px-6" id="notifications-panel-close" @click="toggleNotificationsPanel">
                Close
            </div>
        </div>

        <div class="px-4 border-b border-80 overflow-y-auto h-full">
            <div>Notification Messages</div>
            <!-- <infinite-loading @infinite="getNotifications"></infinite-loading> -->
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        toggleNotificationsPanel: function () {
            this.$emit('toggleNotificationsPanel')
        },
    },
}
</script>


<style scoped>
    /* Notification Panel */

    .notifications-panel {
        z-index: 999;
        position: fixed !important;
        right: 0;
        top: 0;
        width: 340px;
        height: 100%;
        background-color: #536170;
        padding-bottom: 70px;
    }
    #notifications-panel-close {
        height: 60px;
        line-height: 60px;
        cursor: pointer;
    }
    #notifications-panel-close:hover {
        background-color: #252d37;
    }
</style>

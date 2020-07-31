<template>
    <span v-if="field.value">
        <router-link :to="{
            name: 'detail',
            params: {
                resourceName: field.resourceName ? field.resourceName : resourceName,
                resourceId: field.resourceId ? field.resourceId : field.value
            }
        }"
        :class="field.class ? field.class : defaultClass">
            {{ field.label ? field.label : field.value }}
        </router-link>
    </span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    data() {
        return {
            defaultClass: "no-underline dim text-primary font-bold",
        }
    },
}
</script>

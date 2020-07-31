<template>
    <panel-item :field="field" >
        <template slot="value">
             <router-link :to="{
                name: 'detail',
                params: {
                    resourceName: field.resourceName ? field.resourceName : resourceName,
                    resourceId: field.resourceId ? field.resourceId : resourceId
                }
            }"
            :class="field.class ? field.class : defaultClass">
                {{ field.label ? field.label : field.value }}
            </router-link>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            defaultClass: "no-underline dim text-primary font-bold",
        }
    },
}
</script>

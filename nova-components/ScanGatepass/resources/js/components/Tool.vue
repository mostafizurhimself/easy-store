<template>
    <div>
        <heading class="mb-6">Scan Gatepass</heading>

        <card class="bg-white flex flex-col items-center justify-center" style="min-height: 300px"></card>
    </div>
</template>

<script>
export default {
    metaInfo() {
        return {
            title: "ScanGatepass",
        };
    },
    data() {
        return {
            pass: "GGP201200001",
        };
    },
    mounted() {
        Nova.request()
            .get("/nova-vendor/scan-gatepass/passes?pass="+this.pass)
            .then((response) => {
                console.log(response.data)
            });
    },
};
</script>

<style>
/* Scoped Styles */
</style>

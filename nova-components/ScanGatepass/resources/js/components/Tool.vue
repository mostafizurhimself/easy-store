<template>
  <div>
    <heading class="mb-6">Scan Gatepass</heading>

    <card class="bg-white mb-6 py-4 px-6" style="min-height: 300px">
      <div class="flex justify-center pb-2">
        <input
          type="text"
          placeholder="Enter Gatepass"
          v-model="pass"
          @keyup.enter="submitPass"
          class="rounded-lg py-3 px-4 border border-70 outline-none mt-8"
        />
      </div>

      <div v-if="details">
        <!-- Goods pass -->
        <div
          class="mt-4 border border-40"
          v-if="details.details && details.details.total_bag"
        >
          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Total Bag</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.details.total_bag }}</h5>
            </div>
          </div>

          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Total Cartoon</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.details.total_ctn }}</h5>
            </div>
          </div>

          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Total Poly</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.details.total_poly }}</h5>
            </div>
          </div>
        </div>
        <!-- Goods pass Ends -->

        <!-- Visitor pass -->
        <div class="mt-4 border border-40" v-if="details.visitorName">
          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Name</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.visitorName }}</h5>
            </div>
          </div>

          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Card NO:</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.cardNo }}</h5>
            </div>
          </div>

          <div class="flex border border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Mobile</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.mobile }}</h5>
            </div>
          </div>
        </div>
        <!-- Visitor pass Ends -->

        <!-- Employee pass -->
        <div class="mt-4 border border-40" v-if="details.employeeId">
          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">ID</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.employeeId }}</h5>
            </div>
          </div>

          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Early Leave</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.earlyLeave }}</h5>
            </div>
          </div>

          <div class="flex border border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Reason</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.reason }}</h5>
            </div>
          </div>
        </div>
        <!-- Employee pass Ends -->

        <!-- Manual pass -->
        <div class="mt-4 border border-40" v-if="details.totalQuantity">
          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Number</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.readableId }}</h5>
            </div>
          </div>

          <div class="flex border-b border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Early Leave</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.earlyLeave }}</h5>
            </div>
          </div>

          <div class="flex border border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Quantity</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.totalQuantity }}</h5>
            </div>
          </div>

          <div class="flex border border-40">
            <div class="w-1/4 p-4 bg-30">
              <h5 class="font-normal text-80">Note</h5>
            </div>
            <div class="w-3/4 p-4 break-words">
              <h5 class="text-80">{{ details.note }}</h5>
            </div>
          </div>
        </div>
        <!-- Manual pass Ends -->
      </div>
    </card>
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
      pass: "",
      details: null,
    };
  },
  methods: {
    submitPass() {
      this.getDetailsData(this.pass);
    },
    getDetailsData(pass) {
      Nova.request()
        .get("/nova-vendor/scan-gatepass/passes?pass=" + pass)
        .then((response) => {
          console.log("res data", response.data);
          this.details = response.data;
        });
    },
  },
};
</script>

<style>
/* Scoped Styles */
</style>

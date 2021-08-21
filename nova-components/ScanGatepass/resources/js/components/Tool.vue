<template>
	<div>
		<heading class="mb-6">Scan Gatepass</heading>

		<card class="bg-white mb-6 py-4 px-6" style="min-height: 300px">
			<!-- Input -->
			<div class="flex justify-center pb-2">
				<div>
					<input type="text" placeholder="Enter Gatepass" autofocus v-model.trim="pass" @keyup="submitPass" class="rounded-lg py-3 px-4 border border-70 outline-none mt-8" ref="pass" />
					<div class="text-danger mt-1" v-if="errors">{{ errors }}</div>
				</div>
			</div>
			<!-- Input ends -->

			<div v-if="details">
				<!-- Goods pass -->
				<div class="my-4 border border-40" v-if="details.type == 'goods'">
					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Number</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary">{{ details.readableId }}</h5>
						</div>
					</div>

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

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Status</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary capitalize">{{ details.status }}</h5>
						</div>
					</div>
				</div>
				<!-- Goods pass Ends -->

				<!-- Visitor pass -->
				<div class="my-4 border border-40" v-if="details.type == 'visitor'">
					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Number</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary">{{ details.readableId }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Card No:</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.cardNo }}</h5>
						</div>
					</div>

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
							<h5 class="font-normal text-80">Mobile</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.mobile }}</h5>
						</div>
					</div>

					<div class="flex border border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Purpose</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.purpose }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Status</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary capitalize">{{ details.status }}</h5>
						</div>
					</div>
				</div>
				<!-- Visitor pass Ends -->

				<!-- Employee pass -->
				<div class="my-4 border border-40" v-if="details.type == 'employee'">
					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Number</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary">{{ details.readableId }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Employee ID</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.employeeId }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Gender</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 capitalize">{{ details.employee.gender }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Name</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.employee.firstName }} {{ details.employee.lastName }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Mobile</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.employee.mobile }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Approved In</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.approvedInReadable }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Approved Out</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.approvedOutReadable }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Out</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.outTimeReadable }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">In</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.inTimeReadable }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Early Leave</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ `${details.earlyLeave == 0 ? "No" : "Yes"}` }}</h5>
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

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Status</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary capitalize">{{ details.status }}</h5>
						</div>
					</div>
				</div>
				<!-- Employee pass Ends -->

				<!-- Manual pass -->
				<div class="my-4 border border-40" v-if="details.type == 'manual'">
					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Number</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary">{{ details.readableId }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Items</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<div class="overflow-hidden relative rounded-lg bg-white shadow border border-60">
								<table class="table w-full table-default nova-resource-table">
									<thead>
										<tr class="font-normal">
											<th>DESCRIPTION</th>
											<th>QUANTITY</th>
										</tr>
									</thead>
									<tbody v-for="item in details.items" :key="item.index">
										<tr class="nova-resource-table-row">
											<td class="text-sm" style="height: 2rem">{{ item.description }}</td>
											<td class="text-sm" style="height: 2rem">{{ item.quantity }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="flex border border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Total Quantity</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.totalQuantity }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Total Bag</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.summary.total_bag }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Total Ctn</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.summary.total_ctn }}</h5>
						</div>
					</div>

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Total Poly</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80">{{ details.summary.total_poly }}</h5>
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

					<div class="flex border-b border-40">
						<div class="w-1/4 p-4 bg-30">
							<h5 class="font-normal text-80">Status</h5>
						</div>
						<div class="w-3/4 p-4 break-words">
							<h5 class="text-80 text-primary capitalize">{{ details.status }}</h5>
						</div>
					</div>
				</div>
				<!-- Manual pass Ends -->

				<!-- Button -->
				<div class="text-center" v-if="details.type == 'employee' && details.status == 'passed' && details.in == null">
					<a @click="passDetailData" class="btn btn-default btn-primary cursor-pointer">CheckIn</a>
				</div>

				<div class="text-center" v-if="details.status == 'confirmed'">
					<a @click="passDetailData" class="btn btn-default btn-primary cursor-pointer">Pass</a>
				</div>
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
			errors: null,
			pass: "",
			details: null,
		};
	},
	methods: {
		submitPass() {
			this.errors = null;
			this.details = null;
			this.getDetailsData(this.pass);
		},
		getDetailsData(pass) {
			Nova.request()
				.get("/nova-vendor/scan-gatepass/passes?pass=" + pass)
				.then((response) => {
					console.log("res data", response.data);
					this.details = response.data;
				})
				.catch((err) => {
					if (err.response.status == 422) {
						this.details = null;
						console.log(this.errors);
						this.errors = err.response.data.errors.pass[0];
					} else if (err.response.status == 404) {
						this.errors = err.response.data.message;
					} else {
						console.log(err.response.data);
					}
				});
		},
		passDetailData() {
			Nova.request()
				.post(`/nova-vendor/scan-gatepass/passes`, {
					pass: this.pass,
				})
				.then((response) => {
					//   console.log("res data", response.data);
					this.$toasted.show(response.data.message, {
						type: "success",
					});
					this.details = null;
					this.pass = "";
					this.$refs.pass.focus();
				})
				.catch((err) => {
					if (err.response.status == 422) {
						// console.log(this.errors);
						this.errors = err.response.data.errors.pass[0];
						this.$toasted.show(err.response.data.errors.pass[0], {
							type: "error",
						});
					}
					// else if (err.response.status == 404) {
					//     this.$toasted.show(err.response.data.message, {
					//         type: "error",
					//     });
					// }
					// else {
					// 	console.log(err.response.data);
					// }
				});
		},
		addShotcuts(e) {
			if (e.key == "Enter") {
				this.passDetailData();
			}
		},
	},
	mounted() {
		// Listening for window Keyboard press
		window.addEventListener("keyup", this.addShotcuts);
	},
};
</script>

<style>
/* Scoped Styles */
</style>

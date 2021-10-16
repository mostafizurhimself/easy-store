<template>
	<div>
		<heading class="mb-6">Take Attendance</heading>

		<card class="bg-white flex flex-col items-center justify-center" style="min-height: 300px">
			<!-- Input -->
			<div class="flex flex-col justify-center pb-2">
				<select v-model="type" class="rounded-lg py-3 px-4 border border-70 outline-none mt-8">
					<option value="1">Check In</option>
					<option value="0">Check Out</option>
				</select>
				<input type="text" placeholder="Enter Employee Id" autofocus v-model.trim="employeeId" @keyup="handleChange" class="rounded-lg py-3 px-4 border border-70 outline-none mt-8" ref="input" />
			</div>
		</card>
	</div>
</template>

<script>
import debounce from "lodash/debounce";

export default {
	metaInfo() {
		return {
			title: "ScanAttendance",
		};
	},
	data() {
		return {
			employeeId: null,
			type: 1,
		};
	},

	methods: {
		handleChange: debounce(function () {
			this.takeAttendance();
		}, 500),
		takeAttendance() {
			if (!this.employeeId) {
				return;
			}
			Nova.request()
				.post(`/nova-vendor/scan-attendance/take-attendance`, {
					type: this.type,
					employeeId: this.employeeId,
				})
				.then((response) => {
					this.$toasted.show(response.data.message, {
						type: "success",
					});
					this.employeeId = "";
					this.$refs.input.focus();
				})
				.catch((err) => {
					if (err.response.status == 422) {
						this.$toasted.show(
							err.response.data.errors.employeeId[0],
							{
								type: "error",
							}
						);
					} else {
						this.$toasted.show(err.response.data.message, {
							type: "error",
						});
					}
				});
		},
	},
	mounted() {
		//
	},
};
</script>

<style>
/* Scoped Styles */
</style>

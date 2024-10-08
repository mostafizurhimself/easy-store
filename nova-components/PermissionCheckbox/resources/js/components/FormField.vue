<template>
  <default-field :field="field" :full-width-content="true">
    <template slot="field">
      <div class="w-full mb-4">
        <span class="ml-auto btn btn-default btn-primary mr-3" @click="checkAll()" style="cursor:pointer">{{ __('Select all')}}</span>
        <span class="ml-auto btn btn-default btn-danger" @click="uncheckAll()"  style="cursor:pointer">{{ __('Do not select any') }}</span>
      </div>
      <div class="w-full max-col-2" v-if="field.withGroups">
        <div v-for="(permissions, group) in field.options" :key="group" class="mb-4">
          <h3 class="my-2">{{ __(group) }}</h3>
          <div
            v-for="(permission, option) in permissions"
            :key="permission.option"
            class="flex mb-2"
          >
            <checkbox
              :value="permission.option"
              :checked="isChecked(permission.option)"
              @input="toggleOption(permission.option)"
              class="pr-2"
            />
            <label
              :for="field.name"
              v-text="permission.label"
              @click="toggleOption(permission.option)"
              class="w-full ml-2
              "
            ></label>
          </div>
        </div>
      </div>
      <div class="w-full max-col-2" v-else>
        <div v-for="(label, option) in field.options" :key="option" class="flex mb-2">
          <checkbox
            :value="option"
            :checked="isChecked(option)"
            @input="toggleOption(option)"
            class="pr-2"
          />
          <label :for="field.name" v-text="label" @click="toggleOption(option)" class="w-full"></label>
        </div>
      </div>
      <p v-if="hasError" class="my-2 text-danger">{{ firstError }}</p>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ["resourceName", "resourceId", "field"],
    methods: {
        checkAll() {
        // With Groups
        if (this.field.withGroups) {
            let permissions = _.flatMap(this.field.options);
            for (var i = 0; i < permissions.length; i++) {
            this.check(permissions[i].option);
            }
        }
        // Todo: Without Groups
        },
        uncheckAll() {
        // With Groups
        if (this.field.withGroups) {
            let permissions = _.flatMap(this.field.options);
            for (var i = 0; i < permissions.length; i++) {
            this.uncheck(permissions[i].option);
            }
        }
        // Todo: Without Groups
        },
        isChecked(option) {
        return this.value ? this.value.includes(option) : false;
        },
        check(option) {
        if (!this.isChecked(option)) {
            this.value.push(option);
        }
        },
        uncheck(option) {
        if (this.isChecked(option)) {
            this.$set(this, "value", this.value.filter(item => item != option));
        }
        },
        toggleOption(option) {
        if (this.isChecked(option)) {
            return this.uncheck(option);
        }
        this.check(option);
        },
        /*
        * Set the initial, internal value for the field.
        */
        setInitialValue() {
        this.value = this.field.value || [];
        },
        /**
        * Fill the given FormData object with the field's internal value.
        */
        fill(formData) {
        console.log(this.value);
        formData.append(this.field.attribute, this.value || []);
        },
        /**
        * Update the field's internal value.
        */
        handleChange(value) {
        this.value = value;
        }
    }
};
</script>

<style>
.max-col-3 {
  -moz-column-count: 3;
  -webkit-column-count: 3;
  column-count: 3;
  white-space: nowrap;
}
.max-col-2 {
  -moz-column-count: 2;
  -webkit-column-count: 2;
  column-count: 2;
  white-space: nowrap;
}
</style>

<template>
  <div>
    <div class="modal fade" id="add-edit-item" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title modal-title-font" id="exampleModalLabel">{{ title }}</div>
          </div>
          <ValidationObserver v-slot="{ handleSubmit }">
            <form class="form-horizontal" id="form" @submit.prevent="handleSubmit(onSubmit)" autocomplete="off">
              <!-- Hidden name field for accessibility -->
              <input type="text" name="name" autocomplete="name" style="display:none">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <ValidationProvider name="Location Name" mode="eager" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label for="locationName">Location Name <span class="error">*</span></label>
                        <input type="text" class="form-control" :class="{'error-border': errors[0]}"
                               v-model="locationName" placeholder="location Name" >
                        <span class="error-message"> {{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-12 col-md-6">
                    <ValidationProvider name="LocationShortName" mode="eager" rules="" v-slot="{ errors }">
                      <div class="form-group">
                        <label for="staffName">Location Short Name <span class="error">*</span></label>
                        <input type="text" class="form-control" :class="{'error-border': errors[0]}" id="shortName"
                               v-model="shortName" name="shortName" placeholder="Short Name">
                        <span class="error-message"> {{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>

                  <div class="col-12 col-md-6">
                    <ValidationProvider name="Status" mode="eager" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label for="status">Status <span class="error">*</span></label>
                        <select class="form-control" id="status" v-model="status">
                          <option value="Y">Active</option>
                          <option value="N">Inactive</option>
                        </select>
                        <span class="error-message"> {{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <submit-form v-if="buttonShow" :name="buttonText"/>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {bus} from "../../app";
import {Common} from "../../mixins/common";

export default {
  mixins: [Common],
  data() {
    return {
      title: '',
      locationCode: '',
      shortName: '',
      locationName: '',
      status: '',
      buttonText: '',
      actionType: '',
      buttonShow: false,
    }
  },
  mounted() {
    $('#add-edit-item').on('hidden.bs.modal', () => {
      this.$emit('changeStatus');
    });
    bus.$on('add-edit-location', (row) => {
      this.resetForm();
      if (row) {
        this.axiosGet('setup/get-location-info/' + row.LocationCode, (response) => {
          const location = response;
          this.title = 'Update Location';
          this.buttonText = 'Update';
          this.locationCode = location.LocationCode;
          this.locationName = location.LocationName;
          this.shortName = location.LocationShortName;
          this.status = location.Status;
          this.buttonShow = true;
          this.actionType = 'edit';
        }, (error) => {
          console.log('Error fetching location info:', error);
        });
      } else {
        this.title = 'Add Location';
        this.buttonText = 'Add';
        this.buttonShow = true;
        this.actionType = 'add';

      }
      $("#add-edit-item").modal("toggle");
    });
  },
  destroyed() {
    bus.$off('add-edit-location');
  },
  methods: {
    resetForm() {
      this.locationCode = '';
      this.staffId = '';
      this.locationName = '';
      this.status = 'Y';
      this.buttonShow = false;
    },

    onSubmit() {
      this.$store.commit('submitButtonLoadingStatus', true);
      const url = this.actionType === 'add' ? 'setup/store-location' : 'setup/store-location';
      this.axiosPost(url, {
        locationCode: this.locationCode,
        locationName: this.locationName,
        locationShortName: this.shortName,
        status: this.status,
      }, (response) => {
        this.successNoti(response.message);
        $("#add-edit-item").modal("toggle");
        bus.$emit('refresh-datatable');
        this.$store.commit('submitButtonLoadingStatus', false);
      }, (error) => {
        this.errorNoti(error);
        this.$store.commit('submitButtonLoadingStatus', false);
      });
    },
  }
}
</script>

<style src="../../../../node_modules/vue-multiselect/dist/vue-multiselect.min.css"></style>
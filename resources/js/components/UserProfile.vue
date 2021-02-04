<template>
  <div class="uk-container uk-padding-remove uk-margin-bottom">
    <div class="uk-margin-large-bottom uk-f uk-flex-center" uk-grid>
      <div class="uk-width-1-1 uk-width-5-6@s uk-width-3-5@m">
        <div
          class="uk-card uk-card-default uk-padding-remove uk-border-rounded"
        >
          <div class="uk-card-header uk-padding-small">
            <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">
              PROFILE
            </h2>
            <p class="uk-margin-remove-top">
              View and Edit your profile
            </p>
          </div>
          <div class="uk-card-body uk-padding-small">
            <a
              href="#"
              @click="toggle_edit_mode()"
              class="uk-button uk-button-small uk-position-top-right uk-background-primary white-text"
              uk-icon="plus"
              >{{ edit_mode ? "View" : "Edit" }} Profile</a
            >
            <form
              method="POST"
              enctype="multipart/form-data"
              class="uk-form-stacked"
            >
              <div class="uk-margin">
                <label for="name" class="uk-form-label">
                  Name
                </label>
                <div class="uk-form-control">
                  <input
                    id="name"
                    class="uk-input"
                    name="name"
                    autofocus
                    :disabled="!edit_mode"
                    v-model="edit_data.name"
                  />
                  <span v-show="form_error.name" class="uk-text-danger">{{
                    form_error.name
                  }}</span>
                </div>
              </div>
              <div class="uk-margin">
                <label for="email" class="uk-form-label">
                  Email
                </label>
                <div class="uk-form-control">
                  <input
                    id="email"
                    class="uk-input"
                    name="email"
                    :disabled="!edit_mode"
                    v-model="edit_data.email"
                  />
                  <span v-show="form_error.email" class="uk-text-danger">{{
                    form_error.email
                  }}</span>
                </div>
              </div>
              <div class="uk-margin">
                <label for="phone" class="uk-form-label">
                  Phone
                </label>
                <div class="uk-form-control">
                  <input
                    id="phone"
                    class="uk-input"
                    name="phone"
                    :disabled="!edit_mode"
                    v-model="edit_data.phone"
                  />
                  <span v-show="form_error.phone" class="uk-text-danger">{{
                    form_error.phone
                  }}</span>
                </div>
              </div>
              <div class="uk-grid-small uk-child-width-1-2" uk-grid>
                <div class="">
                  <label for="state_id" class="uk-form-label">
                    State
                  </label>
                  <div class="uk-form-control">
                    <select
                      id="state_id"
                      class="uk-input"
                      name="state_id"
                      :disabled="!edit_mode"
                      v-model="edit_data.state_id"
                    >
                      <option value="">-- Select State --</option>
                      <option
                        v-for="(state, x) in states"
                        :value="state.id"
                        :key="x"
                        >{{ state.name }}</option
                      >
                    </select>
                    <span v-show="form_error.state_id" class="uk-text-danger">{{
                      form_error.state_id
                    }}</span>
                  </div>
                </div>
                <div class="">
                  <label for="lga_id" class="uk-form-label">
                    Lga
                  </label>
                  <div class="uk-form-control">
                    <select
                      id="lga_id"
                      class="uk-input"
                      name="lga_id"
                      :disabled="!edit_mode"
                      v-model="edit_data.lga_id"
                    >
                      <option value="">-- Select Lga --</option>
                      <option
                        v-for="(lga, y) in sel_state_lgas"
                        :value="lga.id"
                        :key="y"
                        >{{ lga.name }}</option
                      >
                    </select>
                    <span v-show="form_error.lga_id" class="uk-text-danger">{{
                      form_error.lga_id
                    }}</span>
                  </div>
                </div>
              </div>
              <div v-show="edit_mode" class="uk-margin">
                <div class="uk-form-control uk-text-center">
                  <button
                    @click="update_profile"
                    type="button"
                    class="uk-button uk-button-primary uk-width-2-3"
                  >
                    Update
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      loading: false,
      edit_mode: false,
      profile_data: {
        state_id: "",
        lga_id: "",
        name: "",
        phone: "",
        username: "",
        email: ""
      },
      form_error: {
        state_id: null,
        lga_id: null,
        name: null,
        phone: null,
        username: null,
        email: null
      },
      edit_data: {
        state_id: "",
        lga_id: "",
        name: "",
        phone: "",
        username: "",
        email: ""
      },
      base_url: window.location.origin,
      Toast: this.$swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        onOpen: toast => {
          toast.addEventListener("mouseenter", this.$swal.stopTimer);
          toast.addEventListener("mouseleave", this.$swal.resumeTimer);
        }
      }),
      axios_config: {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      }
    };
  },
  computed: {
    sel_state_lgas() {
      this.edit_data.lga_id = "";
      return this.lgas.filter(x => x.state_id == this.edit_data.state_id);
    }
  },
  methods: {
    load_data() {
      this.loading = !this.loading;
      axios
        .get(`${this.base_url}/api/user/profile/view`)
        .then(res => {
          this.profile_data = this.edit_data = res.data.details;
          this.form_error = {
            state_id: null,
            lga_id: null,
            name: null,
            phone: null,
            username: null,
            email: null
          };
          this.Toast.fire({
            icon: "success",
            title: "Profile Data loaded"
          });
          this.loading = !this.loading;
        })
        .catch(err => {
          this.Toast.fire({
            icon: "error",
            title: "Unable to load profile data..."
          });
          this.loading = !this.loading;
        });
    },
    update_profile() {
      this.$swal
        .fire({
          title: "Update?",
          text: "You won't be able to revert this!",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel"
        })
        .then(result => {
          if (result.value) {
            axios
              .post(
                `${window.location.origin}/api/user/profile/update`,
                this.edit_data,
                this.axios_config
              )
              .then(res => {
                this.profile_data = this.edit_data = {
                  state_id: res.data.details.state_id,
                  lga_id: res.data.details.lga_id,
                  name: res.data.details.name,
                  phone: res.data.details.phone,
                  email: res.data.details.email
                };
                this.toggle_edit_mode();
                this.$swal.fire({
                  text: res.data.message,
                  icon: "success"
                });
                return;
              })
              .catch(err => {
                if (err.response && err.response.status === 422) {
                  if (err.response.data.errors.name) {
                    this.form_error.name = err.response.data.errors.name.join(
                      ", "
                    );
                  }
                  if (err.response.data.errors.email) {
                    this.form_error.email = err.response.data.errors.email.join(
                      ", "
                    );
                  }
                  if (err.response.data.errors.name) {
                    this.form_error.phone = err.response.data.errors.phone.join(
                      ", "
                    );
                  }
                  if (err.response.data.errors.state_id) {
                    this.form_error.state_id = err.response.data.errors.state_id.join(
                      ", "
                    );
                  }
                  if (err.response.data.errors.lga_id) {
                    this.form_error.lga_id = err.response.data.errors.lga_id.join(
                      ", "
                    );
                  }
                }
                this.$swal.fire({
                  title: "Failed: Updating Failed",
                  icon: "error",
                  text: `An error occurred while updating profile`
                });
              });
          }
        });
    },
    toggle_edit_mode() {
      this.edit_data = this.profile_data;
      this.edit_mode = !this.edit_mode;
    }
  },
  created() {
    this.load_data();
  },
  props: {
    states: {
      required: true,
      type: Array,
      default: null
    },
    lgas: {
      required: true,
      type: Array,
      default: null
    }
  }
};
</script>

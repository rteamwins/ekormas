<template>
  <div class="uk-container uk-padding-remove uk-margin-bottom">
    <div class="uk-margin-large-bottom" uk-grid>
      <div class="uk-width-1-1">
        <div
          class="uk-card uk-card-default uk-padding-remove uk-border-rounded"
        >
          <div class="uk-card-header uk-padding-small">
            <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">
              ALERTS
            </h2>
            <p class="uk-margin-remove-top">
              All Alerts
            </p>
          </div>
          <div class="uk-card-body uk-padding-remove">
            <a
              href="#"
              @click="new_alert()"
              class="uk-button uk-button-small uk-position-top-right uk-background-primary white-text"
              uk-icon="plus"
              >New Alert</a
            >
            <table
              class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
            >
              <thead>
                <tr>
                  <th>#</th>
                  <th>MESSAGE</th>
                  <th>STATUS</th>
                  <th>DATE</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody v-if="Object.keys(alerts).length > 0">
                <tr v-for="(alert, i) in alerts" :key="`alert_${i}`">
                  <td>
                    <span class="uk-hidden@m uk-text-bold">#: </span>
                    {{ i + 1 }}
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Message: </span>
                    <span>
                      {{ alert.message }}
                    </span>
                  </td>

                  <td>
                    <span class="uk-hidden@m uk-text-bold">Status: </span>
                    <span class="uk-label green">
                      {{ alert.status }}
                    </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Date: </span>
                    {{ moment(alert.created_at).fromNow() }}
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Action: </span>
                    <span
                      v-show="alert.status == 'pending'"
                      title="Enable Alert"
                      style="cursor:pointer;"
                      @click="enable_alert(alert.id)"
                      class="uk-icon-button green-text"
                      uk-icon="check"
                    ></span>
                    <span
                      v-show="alert.status == 'active'"
                      title="Disable Alert"
                      style="cursor:pointer;"
                      @click="disable_alert(alert.id)"
                      class="uk-icon-button red-text text-lighten-2"
                      uk-icon="close"
                    ></span>
                    <span
                      title="Edit Alert"
                      style="cursor:pointer;"
                      @click="edit_alert(alert.id, i)"
                      class="uk-icon-button blue-text"
                      uk-icon="file-edit"
                    ></span>
                    <span
                      title="Delete Alert"
                      style="cursor:pointer;"
                      @click="delete_alert(alert.id)"
                      class="uk-icon-button red-text"
                      uk-icon="trash"
                    ></span>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td class="uk-text-center" colspan="5">
                    <span class="uk-label cyan"> No Data to Display</span>
                  </td>
                </tr>
              </tbody>
            </table>
            <div
              v-show="alert_pagination_data.page_count > 1"
              class="uk-flex-center"
              uk-margin
            >
              <paginate
                v-model="alert_pagination_data.current_page"
                :page-count="alert_pagination_data.page_count"
                :page-range="3"
                :margin-pages="2"
                :prev-text="'<span uk-pagination-previous></span>'"
                :next-text="'<span uk-pagination-next></span>'"
                :container-class="'uk-pagination uk-flex-center'"
                :active-class="'uk-active'"
                :disable-class="'uk-disabled'"
                :click-handler="alert_page_swap"
              >
              </paginate>
            </div>
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
      alerts: [],
      base_url: window.location.origin,
      alert_pagination_data: {
        record_count: 0,
        page_count: 0,
        current_page: 1
      },
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
      })
    };
  },
  methods: {
    load_data(page = 1) {
      this.loading = !this.loading;
      axios
        .get(`${this.base_url}/api/alert/list?page=${page}`)
        .then(res => {
          this.alerts = res.data.data.map(x => ({ ...x, show: false }));
          this.load_alert_pagination_data(
            res.data.last_page,
            res.data.current_page,
            res.data.total
          );
          this.Toast.fire({
            icon: "success",
            title: "New data loaded..."
          });
          this.loading = !this.loading;
        })
        .catch(err => {
          this.Toast.fire({
            icon: "error",
            title: "Unable to load new data..."
          });
          this.loading = !this.loading;
        });
    },
    load_alert_pagination_data(last_page, current_page, total_records) {
      this.alert_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    alert_page_swap(page = this.alert_pagination_data.current_page) {
      if (page > this.alert_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    enable_alert(alert_id) {
      let current_page = this.alert_pagination_data.current_page;
      this.$swal
        .fire({
          title: "Confirm?",
          text: "You won't be able to revert this!",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel"
        })
        .then(result => {
          if (result.value) {
            axios
              .get(`${window.location.origin}/api/alert/enable/${alert_id}`)
              .then(res => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: `Success: ${res.statusText}`,
                  text: `${res.data}`,
                  icon: "success"
                });
                return;
              })
              .catch(err => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: "Failed: " + `${err.response.statusText}`,
                  icon: "error",
                  text: `${err.response.data.message}`
                });
              });
          }
        });
    },
    disable_alert(alert_id) {
      let current_page = this.alert_pagination_data.current_page;
      this.$swal
        .fire({
          title: "Confirm?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel"
        })
        .then(result => {
          if (result.value) {
            axios
              .get(`${window.location.origin}/api/alert/disable/${alert_id}`)
              .then(res => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: `Success: ${res.statusText}`,
                  text: `${res.data}`,
                  icon: "success"
                });
                return;
              })
              .catch(err => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: "Failed: " + `${err.response.statusText}`,
                  icon: "error",
                  text: `${err.response.data.message}`
                });
              });
          }
        });
    },
    delete_alert(alert_id) {
      let current_page = this.alert_pagination_data.current_page;
      this.$swal
        .fire({
          title: "Confirm?",
          text: "You won't be able to revert this!",
          icon: "error",
          showCancelButton: true,
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel"
        })
        .then(result => {
          if (result.value) {
            axios
              .get(`${window.location.origin}/api/alert/delete/${alert_id}`)
              .then(res => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: `Success: ${res.statusText}`,
                  text: `${res.data}`,
                  icon: "success"
                });
                return;
              })
              .catch(err => {
                this.load_data(current_page);
                this.$swal.fire({
                  title: "Failed: " + `${err.response.statusText}`,
                  icon: "error",
                  text: `${err.response.data.message}`
                });
              });
          }
        });
    },
    edit_alert(alert_id, i) {
      let current_page = this.alert_pagination_data.current_page;
      let alert_msg = this.alerts[i].message;
      const axios_config = {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      };

      this.$swal.fire({
        title: "Edit This Alert Message?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Edit",
        cancelButtonText: "Cancel",
        input: "textarea",
        inputPlaceholder: "Type your message here...",
        inputValue: alert_msg,
        showLoaderOnConfirm: true,
        preConfirm: msg => {
          let alert_data = {
            message: msg
          };
          return axios
            .post(
              `${window.location.origin}/api/alert/update/${alert_id}`,
              alert_data,
              axios_config
            )
            .then(res => {
              this.load_data(current_page);
              this.$swal.fire({
                title: `Success: ${res.statusText}`,
                text: `${res.data}`,
                icon: "success"
              });
              return;
            })
            .catch(err => {
              this.load_data(current_page);
              this.$swal.fire({
                title: "Failed: " + `${err.response.statusText}`,
                icon: "error",
                text: `${err.response.data.message}`
              });
            });
        }
      });
    },
    new_alert() {
      let current_page = this.alert_pagination_data.current_page;
      const axios_config = {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      };

      this.$swal.fire({
        title: "Create New Alert?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Create",
        cancelButtonText: "Cancel",
        input: "text",
        inputPlaceholder: "Type your message here...",
        inputValue: "",
        showLoaderOnConfirm: true,
        preConfirm: msg => {
          let alert_data = {
            message: msg
          };
          return axios
            .post(
              `${window.location.origin}/api/alert/new`,
              alert_data,
              axios_config
            )
            .then(res => {
              this.load_data(current_page);
              this.$swal.fire({
                title: `Success: ${res.statusText}`,
                text: `${res.data}`,
                icon: "success"
              });
              return;
            })
            .catch(err => {
              this.load_data(current_page);
              this.$swal.fire({
                title: "Failed: " + `${err.response.statusText}`,
                icon: "error",
                text: `${err.response.data.message}`
              });
            });
        }
      });
    }
  },
  created() {
    this.load_data(1);
  },
  props: {
    init_alerts: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

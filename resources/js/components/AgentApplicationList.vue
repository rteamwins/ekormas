<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>ID CARD</th>
          <th>COUNTRY</th>
          <th>LOCATION</th>
          <th>ADDRESS</th>
          <th>STATUS</th>
          <th>USER</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(applications).length > 0">
        <tr v-for="(application, i) in applications" :key="`application_${i}`">
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ i + 1 }}
          </td>
          <td>
            <img
              :src="application.id_card"
              class="uk-border-rounded"
              width="60"
              height="60"
              @click="view_image(application.id_card)"
            />
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Country: </span>
            {{ application.country.name }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Location: </span>
            {{ application.state.name }}, {{ application.lga.name }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Address: </span>
            {{ application.address }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ application.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">User: </span>
            <span
              title="View Applicant"
              style="cursor:pointer;"
              @click="view_user(application.user)"
              class="uk-icon-button blue-text"
              uk-icon="user"
            ></span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Date: </span>
            {{ moment(application.created_at).fromNow() }}
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td class="uk-text-center" colspan="9">
            <span class="uk-label cyan"> No Data to Display</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      loading: false,
      applications: [],
      base_url: window.location.origin,
      application_pagination_data: {
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
    number_format(x) {
      return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    },
    view_user(user) {
      this.$swal.fire({
        title: user.name,
        html: `<b>Phone:</b> ${user.phone}<br><b>Username:</b> ${
          user.username
        }<br><b>Email:</b> ${
          user.email
        }<br><b>Wallet:</b> $${this.number_format(user.wallet)}<br>`,
        confirmButtonText: "Close"
      });
    },
    load_data(page = 1) {
      this.loading = !this.loading;
      axios
        .get(`${this.base_url}/api/agent/application/list?page=${page}`)
        .then(res => {
          this.applications = res.data.data.map(x => ({ ...x, show: false }));
          this.load_application_pagination_data(
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
    load_application_pagination_data(last_page, current_page, total_records) {
      this.application_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    application_page_swap(
      page = this.application_pagination_data.current_page
    ) {
      if (page > this.application_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    view_image(image) {
      this.$swal.fire({
        reverseButtons: true,
        confirmButtonText: "Ok",
        showCancelButton: false,
        html: `<img data-src='${image}' width='400' height='200' style='height:50vh;object-fit:contain;' class='uk-width-1-1 uk-border-rounded' uk-img>`
      });
    },
    enable_application(application_id) {
      let current_page = this.application_pagination_data.current_page;
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
              .get(
                `${window.location.origin}/api/application/enable/${application_id}`
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
    disable_application(application_id) {
      let current_page = this.application_pagination_data.current_page;
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
              .get(
                `${window.location.origin}/api/application/disable/${application_id}`
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
    init_applications: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

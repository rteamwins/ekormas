<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>AMOUNT</th>
          <th>WALLET ADDRESS</th>
          <th>STATUS</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(bitcoin_requests).length > 0">
        <tr
          v-for="(bitcoin_request, i) in bitcoin_requests"
          :key="`bitcoin_request_${i}`"
        >
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ i + 1 }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Amount: </span>
            <span> ${{ number_format(bitcoin_request.amount) }} </span>
            <span
              style="cursor:pointer;"
              title="Copy Amount to Clipboard"
              @click="copyValueToClipboard(bitcoin_request.amount)"
              class="uk-icon-button"
              uk-icon="copy"
            ></span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Address: </span>
            <span class="uk-text-bold">
              {{ bitcoin_request.destination_wallet_address }}
            </span>
            <span
              style="cursor:pointer;"
              title="Copy Wallet Address to Clipboard"
              @click="
                copyValueToClipboard(bitcoin_request.destination_wallet_address)
              "
              class="uk-icon-button"
              uk-icon="copy"
            ></span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ bitcoin_request.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Date: </span>
            {{ moment(bitcoin_request.created_at).fromNow() }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Action: </span>
            <span
              title="View Requester Detail"
              style="cursor:pointer;"
              @click="view_bitcoin_request_user(bitcoin_request.user)"
              class="uk-icon-button blue-text"
              uk-icon="user"
            ></span>
            <span
              v-show="bitcoin_request.status == 'created'"
              title="Confirm Bitcoin Request Request"
              style="cursor:pointer;"
              @click="confirm_bitcoin_request(bitcoin_request.id)"
              class="uk-icon-button green-text"
              uk-icon="check"
            ></span>
            <span
              v-show="bitcoin_request.status == 'created'"
              title="Decline Bitcoin Request Request"
              style="cursor:pointer;"
              @click="decline_bitcoin_request(bitcoin_request.id)"
              class="uk-icon-button red-text text-lighten-2"
              uk-icon="close"
            ></span>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td class="uk-text-center" colspan="6">
            <span class="uk-label cyan"> No Data to Display</span>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-show="bitcoin_request_pagination_data.page_count > 1"
      class="uk-flex-center"
      uk-margin
    >
      <paginate
        v-model="bitcoin_request_pagination_data.current_page"
        :page-count="bitcoin_request_pagination_data.page_count"
        :page-range="3"
        :margin-pages="2"
        :prev-text="'<span uk-pagination-previous></span>'"
        :next-text="'<span uk-pagination-next></span>'"
        :container-class="'uk-pagination uk-flex-center'"
        :active-class="'uk-active'"
        :disable-class="'uk-disabled'"
        :click-handler="bitcoin_request_page_swap"
      >
      </paginate>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      loading: false,
      bitcoin_requests: [],
      base_url: window.location.origin,
      bitcoin_request_pagination_data: {
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
    load_data(page = 1) {
      this.loading = !this.loading;
      axios
        .get(`${this.base_url}/api/bitcoin/request/list/open?page=${page}`)
        .then(res => {
          this.bitcoin_requests = res.data.data;
          this.load_bitcoin_request_pagination_data(
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
    load_bitcoin_request_pagination_data(
      last_page,
      current_page,
      total_records
    ) {
      this.bitcoin_request_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    bitcoin_request_page_swap(
      page = this.bitcoin_request_pagination_data.current_page
    ) {
      if (page > this.bitcoin_request_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    decline_bitcoin_request(bitcoin_request_id) {
      let current_page = this.bitcoin_request_pagination_data.current_page;
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
                `${window.location.origin}/api/bitcoin/request/decline/${bitcoin_request_id}`
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
    copyValueToClipboard(x) {
      let tt = this.Toast;
      navigator.clipboard.writeText(x).then(
        function() {
          tt.fire({
            icon: "success",
            title: "Code copied to clipboard"
          });
        },
        function() {
          tt.fire({
            icon: "error",
            title: "Unable to copy code"
          });
        }
      );
    },
    view_bitcoin_request_user(bitcoin_request_user) {
      this.$swal.fire({
        title: bitcoin_request_user.name,
        html: bitcoin_request_user.phone + "<br/>"+bitcoin_request_user.username,
        icon: "info",
        confirmButtonText: "Close"
      });
    },
    confirm_bitcoin_request(bitcoin_request_id) {
      let current_page = this.bitcoin_request_pagination_data.current_page;
      this.$swal.fire({
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Sent",
        cancelButtonText: "Cancel",
        title: "Sent Payment?",
        text: "Have you sent the payment to the user wallet address",
        icon: "info",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          const confirm_url = `${window.location.origin}/api/bitcoin/request/confirm/${bitcoin_request_id}`;
          axios
            .get(confirm_url)
            .then(res => {
              this.$swal.fire({
                title: "Successful" + `: ${res.statusText}`,
                icon: "success",
                text: `${res.data}`
              });
            })
            .catch(err => {
              this.$swal.fire({
                title: "Failed: " + `${err.response.statusText}`,
                icon: "error",
                text: `${err.response.data.message}`
              });
            })
            .finally(() => {
              this.load_data();
            });
        }
      });
    }
  },
  created() {
    this.load_data(1);
  },
  props: {
    init_bitcoin_requests: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

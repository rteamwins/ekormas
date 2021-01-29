<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>AMOUNT</th>
          <th>POP</th>
          <th>NETWORK</th>
          <th>MOMO NAME</th>
          <th>MOMO NUMBER</th>
          <th>STATUS</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(lprs).length > 0">
        <tr v-for="(lpr, i) in lprs" :key="`lpr_${i}`">
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ i + 1 }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Amount: </span>
            <span> ${{ number_format(lpr.amount) }} </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">POP: </span>
            <span v-if="lpr.pop === null" class="uk-label green">
              NULL
            </span>
            <span v-else class="uk-label green">
              <button
                class="uk-button uk-button-link uk-link-reset"
                type="button"
                @click="view_pop(lpr.pop)"
              >
                View POP
              </button>
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Network: </span>
            {{ lpr.bank_name }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">MoMo Name: </span>
            {{ lpr.account_name }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">MoMo Number: </span>
            {{ lpr.account_number }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ lpr.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Date: </span>
            {{ moment(lpr.created_at).fromNow() }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Action: </span>
            <span
              title="View Requester Detail"
              style="cursor:pointer;"
              @click="view_lpr_user(lpr.user)"
              class="uk-icon-button blue-text"
              uk-icon="user"
            ></span>
            <span
              v-show="lpr.status == 'created'"
              title="Confirm Local Pay Request"
              style="cursor:pointer;"
              @click="confirm_lpr(lpr.id)"
              class="uk-icon-button green-text"
              uk-icon="check"
            ></span>
            <span
              v-show="lpr.status == 'created'"
              title="Decline Local Pay Request"
              style="cursor:pointer;"
              @click="decline_lpr(lpr.id)"
              class="uk-icon-button red-text text-lighten-2"
              uk-icon="close"
            ></span>
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
    <div
      v-show="lpr_pagination_data.page_count > 1"
      class="uk-flex-center"
      uk-margin
    >
      <paginate
        v-model="lpr_pagination_data.current_page"
        :page-count="lpr_pagination_data.page_count"
        :page-range="3"
        :margin-pages="2"
        :prev-text="'<span uk-pagination-previous></span>'"
        :next-text="'<span uk-pagination-next></span>'"
        :container-class="'uk-pagination uk-flex-center'"
        :active-class="'uk-active'"
        :disable-class="'uk-disabled'"
        :click-handler="lpr_page_swap"
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
      lprs: [],
      base_url: window.location.origin,
      lpr_pagination_data: {
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
        .get(`${this.base_url}/api/local-pay/request/list/open?page=${page}`)
        .then(res => {
          this.lprs = res.data.data;
          this.load_lpr_pagination_data(
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
    load_lpr_pagination_data(last_page, current_page, total_records) {
      this.lpr_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    lpr_page_swap(page = this.lpr_pagination_data.current_page) {
      if (page > this.lpr_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    decline_lpr(lpr_id) {
      let current_page = this.lpr_pagination_data.current_page;
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
                `${window.location.origin}/api/local-pay/request/decline/${lpr_id}`
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
    view_lpr_user(lpr_user) {
      this.$swal.fire({
        title: lpr_user.name,
        text: lpr_user.phone,
        icon: "info",
        confirmButtonText: "Close"
      });
    },
    async confirm_lpr(lpr_id) {
      let current_page = this.lpr_pagination_data.current_page;
      const { value: file } = await this.$swal.fire({
        title: "Select Proof of payment",
        confirmButtonText: "Select Image",
        html:
          "<img data-src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6BAMAAAB6wkcOAAAAG1BMVEXMzMyWlpacnJyjo6Oqqqq3t7fFxcWxsbG+vr6BtizEAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAC8ElEQVR42u2ZTXPSQBiAI1DgaCwUjwXHj2M5aHtsDjoe0TqOR6hfPcrUH1BGx98tJJsQkndDaN5dPTzPhWSXN0+y2e8EAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/K/0f07CyfnM2fUfhFuevC5k/jYZF3UDmtjDcDDP573P0l/UC2hoD0e5rM+59Fd1Ahrbw8vtO88nP7rbH6BgP85yljvpw/0BCvbwh8noFdJn+wLua5/EjJOLPTYZ7+Kz55/mN1c76daA+9rNYfdDriSTt/42Pv4eH8+rA5rag+DL5mpJ/brON7T45LQ6oLk9rmlJJY42jTlLj7ZtyxagYO+szx5mBX+5k26K3hKgYe+mtahdeKHT7GYsARr2jSUu4dU6+SyXfp1ZLAEq9qV55GmhNvWzamAJULGvkot1d+pcejsVATr2xbpLX/8crVNPdv63SLs7OUDHbk7bpYbUSSuCHKBqX5Q6kX5aGi7tpiCX5fIcm3HOZcmbi0XldhSZeujSbqrwuNyHrIzGZZ1fxk/YFfrP9I9igJI9ebv97ZCW0TIVUQzQsZua3RNGro6ZxogBOvZW8tAdYcKU3pEYoGOfJtq2MGfom+5GDFCx/zLDuM1+agtQsN/chmZwaQnX7Jbt2wC1GfVJDXspQM0+s3TeaR8gBmjZB9ahw2JvUvDysuyQZ9dcxw2zVlzzvQ8DPbtZjte3K67fs62Ifb2N6t5FKbF+X+fC3hEaktzPu7DXH+Nc2A8Y3x3YbXObuRf7AfM6F3ZhTrus6oV17fJ8fuTJXn8t48Jefx3nwl5/DevCXl6/R+nA48Eu7l0cB77sq0Jv17Lt2zixS3tWZ97s0n7dnTe7sFc5CPzZ463ZZ/v2aV3Zkz3qN/FxvBecNgEvdrM/f/7nW7Jeyiavfuw9yycIP/bCd5lR4Nd+JK/WPNkrvsf5sOe+RT4N3NgruTLyl8E/4evt5hv0xwAAAAAAAAAAAAAAAAAAAAAAAAAAAADgUP4CYVaZeUH9jw4AAAAASUVORK5CYII=' width='400' height='200' alt='pop placeholder' style='height:40vh;object-fit:cover;' class='uk-width-1-1 uk-border-rounded' uk-img>",
        input: "file",
        inputAttributes: {
          accept: "image/*",
          "aria-label": "Upload Proof of payment picture"
        }
      });
      if (file) {
        this.$swal.fire({
          showCloseButton: true,
          showCancelButton: true,
          confirmButtonText: "Upload",
          cancelButtonText: "Cancel",
          title: "Upload Proof of payment",
          html: `<img data-src='${URL.createObjectURL(
            file
          )}' width='400' height='200' alt='selected pop image' style='height:40vh;object-fit:cover;' class='uk-width-1-1 uk-border-rounded' uk-img>`,
          showLoaderOnConfirm: true,
          preConfirm: () => {
            let pop_form = new FormData();
            pop_form.append("pop", file);
            const pop_submit_url = `${window.location.origin}/api/local-pay/request/confirm/${lpr_id}`;
            const axios_config = {
              headers: {
                "Content-Type": "multipart/form-data",
                Accept: "application/json"
              }
            };
            axios
              .post(pop_submit_url, pop_form, axios_config)
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
      } else {
        this.$swal
          .fire({
            showCloseButton: true,
            icon: "warning",
            title: "Invalid Selection",
            text: "No Proof of Payment Selected",
            confirmButtonText: "Cancel"
          })
          .then(result => {
            this.$swal.fire(
              "Cancelled!",
              "Local Pay Request Confirmation Cancelled",
              "success"
            );
          });
      }
    },
    view_pop(pop) {
      this.$swal.fire({
        showCloseButton: true,
        confirmButtonText: "Close",
        title: "Viewing POP",
        html: `<img data-src='${pop}' width='400' height='200' alt='pop placeholder' style='height:50vh;object-fit:contain;' class='uk-width-1-1 uk-border-rounded' uk-img>`,
        onDestroy: () => {
          pop = null;
        }
      });
    }
  },
  created() {
    this.load_data(1);
  },
  props: {
    init_lprs: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

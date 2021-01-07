<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>Address</th>
          <th>CODE</th>
          <th>AMOUNT</th>
          <th>FEE</th>
          <th>STATUS</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(kycs).length > 0">
        <tr v-for="(kyc, i) in kycs" :key="`kyc_${i}`">
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ (i+1) }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Address: </span>
            <span class="uk-text-bold">
              {{ kyc.address }}
            </span>
            <span
              style="cursor:pointer;"
              title="Copy Kyc Address to Clipboard"
              @click="copyValueToClipboard(kyc.code)"
              class="uk-icon-button"
              uk-icon="copy"
            ></span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Code: </span>
            <span
              style="cursor:pointer;padding:2px 5px;"
              v-show="kyc.show == true"
              @click.self="kycs[i].show = false"
              class="grey lighten-2 uk-text-bold"
            >
              {{ kyc.code }}
            </span>
            <span
              style="cursor:pointer;padding:3px 6px;"
              @click.self="kycs[i].show = true"
              v-show="kyc.show == false"
              class="grey lighten-2 uk-text-bold"
            >
              *******Click****To****Show******
            </span>

            <span
              title="Copy Kyc Code to Clipboard"
              style="cursor:pointer;"
              @click="copyValueToClipboard(kyc.code)"
              class="uk-icon-button"
              uk-icon="copy"
            >
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Amount: </span>
            ${{ number_format(kyc.amount, 2) }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Fee: </span>
            ${{ number_format(kyc.fee, 2) }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ kyc.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Requested: </span>
            {{ moment(kyc.created_at).fromNow() }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Action: </span>
            <span
              title="View KYC QR Codes"
              style="cursor:pointer;"
              @click="view_kyc_qrcode(kyc)"
              class="uk-icon-button"
              uk-icon="grid"
            ></span>
            <div v-show="false">
              <div :ref="kyc.address">
                <vue-qrcode
                  :size="200"
                  :value="kyc.address"
                />
              </div>
              <div :ref="kyc.code">
                <vue-qrcode
                  :size="200"
                  :value="kyc.code"
                />
              </div>
            </div>
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
    <div v-show="kyc_pagination_data.page_count > 1" class="uk-flex-center" uk-margin>
      <paginate
        v-model="kyc_pagination_data.current_page"
        :page-count="kyc_pagination_data.page_count"
        :page-range="3"
        :margin-pages="2"
        :prev-text="'<span uk-pagination-previous></span>'"
        :next-text="'<span uk-pagination-next></span>'"
        :container-class="'uk-pagination uk-flex-center'"
        :active-class="'uk-active'"
        :disable-class="'uk-disabled'"
        :click-handler="kyc_page_swap"
      >
      </paginate>
    </div>
  </div>
</template>
<script>
import VueQrcode from "vue-qrcode";
export default {
  data() {
    return {
      loading: false,
      kycs: [],
      address_qrcode_value: "",
      code_qrcode_value: "",
      base_url: window.location.origin,
      kyc_pagination_data: {
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
        .get(`${this.base_url}/api/user/kyc/list?page=${page}`)
        .then(res => {
          this.kycs = res.data.data.map(x => ({ ...x, show: false }));
          this.load_kyc_pagination_data(
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
    load_kyc_pagination_data(last_page, current_page, total_records) {
      this.kyc_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    kyc_page_swap(page = this.kyc_pagination_data.current_page) {
      if (page > this.kyc_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    number_format(x) {
      return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
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
    view_kyc_qrcode(kyc) {
      let addressQR = this.$refs[`${kyc.address}`][0].innerHTML;
      let codeQR = this.$refs[`${kyc.code}`][0].innerHTML;

      this.$swal
        .mixin({
          reverseButtons: true,
          confirmButtonText: "Next",
          showCancelButton: true,
          progressSteps: ["1", "2"]
        })
        .queue([
          {
            title: "KYC Address",
            html: addressQR
          },
          {
            title: "KYC Code",
            html: codeQR
          }
        ])
        .then(result => {
          if (result.value) {
            this.$swal.fire({
              icon: "info",
              title: "Information",
              text:
                "Do not share your KYC code with anyone except in events of financial exchange.",
              confirmButtonText: "Okay!"
            });
          }
        });
    }
  },
  created() {
    if (this.init_kycs !== null) {
      this.kycs = this.init_kycs.map(x => ({ ...x, show: false }));
    } else {
      this.load_data(1);
    }
  },
  props: {
    init_kycs: {
      required: false,
      type: Array,
      default: null
    }
  },
  components: {
    VueQrcode
  }
};
</script>

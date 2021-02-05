<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>CODE</th>
          <th>AMOUNT</th>
          <th>PLAN</th>
          <th>STATUS</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(reg_credits).length > 0">
        <tr v-for="(reg_credit, i) in reg_credits" :key="`reg_credit_${i}`">
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ i + 1 }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Code: </span>
            <span
              style="cursor:pointer;padding:2px 5px;"
              v-show="reg_credit.show == true"
              @click.self="reg_credits[i].show = false"
              class="grey lighten-2 uk-text-bold"
            >
              {{ reg_credit.code }}
            </span>
            <span
              style="cursor:pointer;padding:3px 6px;"
              @click.self="reg_credits[i].show = true"
              v-show="reg_credit.show == false"
              class="grey lighten-2 uk-text-bold"
            >
              ****Click**To**Show****
            </span>

            <span
              title="Copy Code to Clipboard"
              style="cursor:pointer;"
              @click="copyValueToClipboard(reg_credit.code)"
              class="uk-icon-button"
              uk-icon="copy"
            >
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Amount: </span>
            ${{ rc_plans[reg_credit.plan] }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Plan: </span>
            <span class="uk-label green">
              {{ reg_credit.plan.toUpperCase() }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ reg_credit.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Requested: </span>
            {{ moment(reg_credit.created_at).fromNow() }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Action: </span>
            <span
              v-show="reg_credit.consumer"
              title="Edit Local Pay Request"
              style="cursor:pointer;"
              @click="view_user(reg_credit.consumer)"
              class="uk-icon-button blue-text"
              uk-icon="user"
            ></span>
            <span
              title="View reg_credit QR Codes"
              style="cursor:pointer;"
              @click="view_reg_credit_qrcode(reg_credit.code)"
              class="uk-icon-button"
              uk-icon="grid"
            ></span>
            <div v-show="false">
              <div :ref="reg_credit.code">
                <vue-qrcode :width="200" :value="reg_credit.code" />
              </div>
            </div>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td class="uk-text-center" colspan="7">
            <span class="uk-label cyan"> No Data to Display</span>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-show="reg_credit_pagination_data.page_count > 1"
      class="uk-flex-center"
      uk-margin
    >
      <paginate
        v-model="reg_credit_pagination_data.current_page"
        :page-count="reg_credit_pagination_data.page_count"
        :page-range="3"
        :margin-pages="2"
        :prev-text="'<span uk-pagination-previous></span>'"
        :next-text="'<span uk-pagination-next></span>'"
        :container-class="'uk-pagination uk-flex-center'"
        :active-class="'uk-active'"
        :disable-class="'uk-disabled'"
        :click-handler="reg_credit_page_swap"
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
      rc_plans: {
        onyx: 70,
        pearl: 130,
        ruby: 310,
        gold: 610,
        sapphire: 1210,
        emerald: 3610,
        diamond: 6010
      },
      reg_credits: [],
      address_qrcode_value: "",
      code_qrcode_value: "",
      base_url: window.location.origin,
      reg_credit_pagination_data: {
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
        .get(`${this.base_url}/api/user/reg_credit/list?page=${page}`)
        .then(res => {
          this.reg_credits = res.data.data.map(x => ({ ...x, show: false }));
          this.load_reg_credit_pagination_data(
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
    load_reg_credit_pagination_data(last_page, current_page, total_records) {
      this.reg_credit_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    reg_credit_page_swap(page = this.reg_credit_pagination_data.current_page) {
      if (page > this.reg_credit_pagination_data.page_count || page < 1) {
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
    view_reg_credit_qrcode(reg_credit_code) {
      let codeQR = this.$refs[`${reg_credit_code}`][0].innerHTML;

      this.$swal
        .fire({
          reverseButtons: true,
          confirmButtonText: "Ok",
          showCancelButton: true,
          html: `<h3 class="uk-text-bold">${reg_credit_code}</h3>${codeQR}`
        })
        .then(result => {
          if (result.value) {
            this.$swal.fire({
              icon: "info",
              title: "Information",
              text:
                "Do not share your Registration Credit code with anyone except in events of financial exchange.",
              confirmButtonText: "Okay!"
            });
          }
        });
    },
    view_user(user) {
      this.$swal.fire({
        title: user.name,
        html: `<b>Phone:</b> ${user.phone}<br><b>Username:</b> ${user.username}<br><b>Email:</b> ${user.email}<br><b>`,
        confirmButtonText: "Close"
      });
    }
  },
  created() {
    if (this.init_reg_credits !== null) {
      this.reg_credits = this.init_reg_credits.map(x => ({
        ...x,
        show: false
      }));
    } else {
      this.load_data(1);
    }
  },
  props: {
    init_reg_credits: {
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

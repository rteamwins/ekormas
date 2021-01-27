<template>
  <div class="uk-container uk-padding-remove uk-margin-bottom">
    <div class="uk-margin-large-bottom" uk-grid>
      <div class="uk-width-1-1">
        <div
          class="uk-card uk-card-default uk-padding-remove uk-border-rounded"
        >
          <div class="uk-card-header uk-padding-small">
            <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">
              ORDERS
            </h2>
            <p class="uk-margin-remove-top">
              All Orders Made
            </p>
          </div>
          <div class="uk-card-body uk-padding-remove">
            <div ref="ordered_products" uk-modal>
              <div class="uk-modal-dialog">
                <button
                  class="uk-modal-close-default"
                  type="button"
                  uk-close
                ></button>
                <div class="uk-modal-header">
                  <h2 class="uk-modal-title">Ordered Products</h2>
                </div>

                <div
                  class="uk-modal-body"
                  style="padding:5px;"
                  uk-overflow-auto
                >
                  <table
                    class="uk-width-1-1 uk-table uk-table-small uk-table-middle uk-table-divider uk-margin-remove"
                  >
                    <tbody class="uk-text-small">
                      <tr
                        v-for="(item, y) in ordered_products"
                        class="uk-width-1-1"
                        :key="'ord_prdt_item_' + y"
                      >
                        <td class="cart_table_data">
                          <img
                            :src="item.product.images[0]"
                            :alt="item.product.title"
                            class="cart_thumb"
                          />
                        </td>
                        <td class="cart_table_data uk-width-1-1">
                          <div class="uk-width-1-1">
                            <p
                              class="uk-text-bold uk-width-1-1 uk-text-truncate uk-margin-remove-vertical"
                            >
                              {{ item.product.title }}
                            </p>
                            <p class=" uk-margin-remove-vertical">
                              <span class="uk-text-bold"
                                >${{ number_format(item.sub_total) }}</span
                              >
                              <span class="uk-text-bold">Qty:</span>
                              {{ item.quantity }}
                            </p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="uk-modal-footer uk-text-right">
                  <button
                    class="uk-button uk-button-default uk-modal-close"
                    type="button"
                  >
                    Close
                  </button>
                </div>
              </div>
            </div>
            <table
              class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
            >
              <thead>
                <tr>
                  <th>#</th>
                  <th>CODE</th>
                  <th>AMOUNT</th>
                  <th>PRODUCTS</th>
                  <th>LOCATION</th>
                  <th>STATUS</th>
                  <th>DATE</th>
                </tr>
              </thead>
              <tbody v-if="Object.keys(orders).length > 0">
                <tr v-for="(order, i) in orders" :key="`order_${i}`">
                  <td>
                    <span class="uk-hidden@m uk-text-bold">#: </span>
                    {{ i + 1 }}
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Code: </span>
                    <span>
                      {{ order.code }}
                    </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Amount: </span>
                    <span> ${{ number_format(order.total_amount) }} </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Products: </span>
                    <button
                      title="View Products"
                      @click="view_product(order.ordered_products)"
                      class="uk-button uk-button-small uk-background-primary white-text"
                      uk-icon="User"
                    >
                      View Products
                    </button>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Location: </span>
                    <button
                      title="View Location"
                      @click="
                        view_location({
                          country: order.country.name,
                          state: order.state.name,
                          lga: order.lga.name,
                          address: order.address
                        })
                      "
                      class="uk-button uk-button-small uk-background-primary white-text"
                      uk-icon="User"
                    >
                      View Location
                    </button>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Status: </span>
                    <span class="uk-label green">
                      {{ order.status }}
                    </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Date: </span>
                    {{ moment(order.created_at).fromNow() }}
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
              v-show="order_pagination_data.page_count > 1"
              class="uk-flex-center"
              uk-margin
            >
              <paginate
                v-model="order_pagination_data.current_page"
                :page-count="order_pagination_data.page_count"
                :page-range="3"
                :margin-pages="2"
                :prev-text="'<span uk-pagination-previous></span>'"
                :next-text="'<span uk-pagination-next></span>'"
                :container-class="'uk-pagination uk-flex-center'"
                :active-class="'uk-active'"
                :disable-class="'uk-disabled'"
                :click-handler="order_page_swap"
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
      orders: [],
      ordered_products: [],
      base_url: window.location.origin,
      order_pagination_data: {
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
        .get(`${this.base_url}/api/order/list?page=${page}`)
        .then(res => {
          this.orders = res.data.data.map(x => ({ ...x, show: false }));
          this.load_order_pagination_data(
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
    load_order_pagination_data(last_page, current_page, total_records) {
      this.order_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    order_page_swap(page = this.order_pagination_data.current_page) {
      if (page > this.order_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    view_location(location) {
      this.$swal.fire({
        title: location.country,
        html: `<b>State:</b> ${location.state}<br><b>Lga:</b> ${location.lga}<br><b>Address:</b> ${location.address}`,
        confirmButtonText: "Close"
      });
    },
    view_product(products) {
      this.ordered_products = products;
      const display_modal = this.$refs["ordered_products"];
      UIkit.modal(display_modal).show();
    }
  },
  created() {
    this.load_data(1);
  },
  props: {
    init_orders: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>
<style scoped>
.cart_thumb {
  width: 50px !important;
  height: 50px !important;
  min-width: 50px !important;
  min-height: 50px !important;
  max-width: 50px !important;
  max-height: 50px !important;
}
.cart_table_data {
  padding: 5px;
}
</style>

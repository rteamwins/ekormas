<template>
  <div>
    <table
      class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
    >
      <thead>
        <tr>
          <th>#</th>
          <th>IMAGE</th>
          <th>CODE</th>
          <th>TITLE</th>
          <th>AMOUNT</th>
          <th>REWARD LEVEL</th>
          <th>DELIVERY DURATION</th>
          <th>STATUS</th>
          <th>DATE</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(products).length > 0">
        <tr v-for="(product, i) in products" :key="`product_${i}`">
          <td>
            <span class="uk-hidden@m uk-text-bold">#: </span>
            {{ i + 1 }}
          </td>
          <td>
            <img
              :src="product.images[0]"
              :alt="product.slug"
              class="uk-border-rounded"
              width="55"
              height="45"
              @click="view_images(product.images)"
            />
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Code: </span>
            <span class="uk-label green">
              {{ product.code }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Title: </span>
            <span>
              {{ product.title }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Amount: </span>
            <span> ${{ number_format(product.amount) }} </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Reward Level: </span>
            <span> Level {{ product.reward_level }} </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Delivery Duration: </span>
            <span> {{ product.delivery_duration }} Days </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Status: </span>
            <span class="uk-label green">
              {{ product.status }}
            </span>
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Date: </span>
            {{ moment(product.created_at).fromNow() }}
          </td>
          <td>
            <span class="uk-hidden@m uk-text-bold">Action: </span>
            <span
              v-show="product.status == 'pending'"
              title="Enable product"
              style="cursor:pointer;"
              @click="enable_product(product.id)"
              class="uk-icon-button green-text"
              uk-icon="check"
            ></span>
            <span
              v-show="product.status == 'active'"
              title="Disable product"
              style="cursor:pointer;"
              @click="disable_product(product.id)"
              class="uk-icon-button red-text text-lighten-2"
              uk-icon="close"
            ></span>
            <span
              title="Edit product"
              style="cursor:pointer;"
              @click="edit_product(product.id)"
              class="uk-icon-button blue-text"
              uk-icon="file-edit"
            ></span>
            <!-- <span
              title="Delete product"
              style="cursor:pointer;"
              @click="delete_product(product.id)"
              class="uk-icon-button red-text"
              uk-icon="trash"
            ></span> -->
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td class="uk-text-center" colspan="8">
            <span class="uk-label cyan"> No Data to Display</span>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-show="product_pagination_data.page_count > 1"
      class="uk-flex-center"
      uk-margin
    >
      <paginate
        v-model="product_pagination_data.current_page"
        :page-count="product_pagination_data.page_count"
        :page-range="3"
        :margin-pages="2"
        :prev-text="'<span uk-pagination-previous></span>'"
        :next-text="'<span uk-pagination-next></span>'"
        :container-class="'uk-pagination uk-flex-center'"
        :active-class="'uk-active'"
        :disable-class="'uk-disabled'"
        :click-handler="product_page_swap"
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
      products: [],
      base_url: window.location.origin,
      product_pagination_data: {
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
        .get(`${this.base_url}/api/product/list?page=${page}`)
        .then(res => {
          this.products = res.data.data.map(x => ({ ...x, show: false }));
          this.load_product_pagination_data(
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
    load_product_pagination_data(last_page, current_page, total_records) {
      this.product_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    product_page_swap(page = this.product_pagination_data.current_page) {
      if (page > this.product_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    view_images(images) {
      let data = [];
      images.forEach((image, i) => {
        data.push({
          html: `<img data-src='${image}' width='400' height='200' style='height:50vh;object-fit:contain;' class='uk-width-1-1 uk-border-rounded' uk-img>`
        });
      });
      this.$swal
        .mixin({
          reverseButtons: true,
          confirmButtonText: "Next",
          showCancelButton: true,
          progressSteps: [...Object.keys(images).map(x => Number(x) + 1)]
        })
        .queue(data);
    },
    enable_product(product_id) {
      let current_page = this.product_pagination_data.current_page;
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
              .get(`${window.location.origin}/api/product/enable/${product_id}`)
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
    disable_product(product_id) {
      let current_page = this.product_pagination_data.current_page;
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
                `${window.location.origin}/api/product/disable/${product_id}`
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
    edit_product(product_id) {
      let current_page = this.product_pagination_data.current_page;
      this.$swal.fire({
        title: "Edit This Product?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Edit",
        cancelButtonText: "Cancel",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          window.location.href = new URL(
            "admin/product/edit/" + product_id,
            window.location.origin
          ).href;
        }
      });
    }
  },
  created() {
    this.load_data(1);
  },
  props: {
    init_products: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

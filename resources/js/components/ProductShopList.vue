<template>
  <div class="uk-grid-collapse" uk-grid>
    <div class="uk-width-1-1 uk-width-3-4@m uk-padding-small">
      <div>
        <div class="uk-grid-small" uk-grid>
          <div
            class="uk-child-width-1-3@m uk-child-width-1-2@s uk-child-width-1-1 uk-grid-small"
            uk-grid
          >
            <!------ads listing start here---->
            <div
              class="ads-listing my-margin"
              v-for="(product, i) in products"
              :key="`product_${i}`"
            >
              <div class="uk-container uk-padding-remove uk-margin ">
                <div
                  class="uk-card uk-card-default uk-card-body uk-padding-remove uk-margin-small my-card uk-link-text"
                >
                  <div class="uk-card-media-top " tabindex="0">
                    <div class=" overflow-hidden">
                      <img
                        class="home_ad_list_thumb uk-transition-scale-up uk-transition-opaque"
                        :src="product.images[0]"
                        alt=""
                        @click="view_images(product.images)"
                      />
                    </div>
                  </div>
                  <div class="uk-card-body uk-text-center uk-padding-remove">
                    <ul class="uk-child-width-expand" uk-tab>
                      <li><a href="#">Details</a></li>
                      <li><a href="#">Description</a></li>
                    </ul>
                    <ul class="uk-switcher">
                      <li>
                        <h4 class="red-text uk-display-block">
                          {{ product.title }}
                        </h4>
                        <h5 class="my-card-title red-text uk-margin-remove-top">
                          ${{ number_format(product.amount) }}
                        </h5>
                      </li>
                      <li>
                        <p class="uk-text-small">
                          {{ product.description }}
                        </p>
                      </li>
                    </ul>

                    <div
                      class="uk-text-small uk-flex uk-flex-center uk-margin-small-bottom"
                      uk-grid
                    >
                      <div class="uk-width-2-3">
                        <a
                          href="#"
                          class="uk-button uk-button-default uk-padding-remove-horizontal uk-border-pill grey darken-3 uk-text-bold white-text uk-text-truncate uk-margin-remove uk-width-1-1 uk-align-center"
                        >
                          <i uk-icon="icon:cart"></i>
                          Add To Cart</a
                        >
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            v-show="product_pagination_data.page_count > 1"
            class="uk-width-1-1 uk-align-center"
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
      </div>
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

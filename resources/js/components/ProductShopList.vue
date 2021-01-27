<template>
  <div>
    <cart :states="states" :lgas="lgas"></cart>
    <div class="uk-padding-small uk-margin-small-top">
      <div
        class="uk-grid-match uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-2@s uk-child-width-1-1 uk-grid-small"
        uk-grid
      >
        <!------ads listing start here---->
        <div
          class="ads-listing my-margin"
          v-for="(product, i) in products"
          :key="`product_${i}`"
        >
          <div
            class="uk-card uk-card-default uk-padding-remove uk-margin-small uk-link-text uk-border-rounded"
          >
            <div class="uk-card-body uk-text-center uk-padding-remove">
              <ul class="uk-child-width-expand uk-margin-remove-bottom" uk-tab>
                <li><a class="uk-text-bold" href="#">Details</a></li>
                <li><a class="uk-text-bold" href="#">Description</a></li>
              </ul>
              <ul class="uk-switcher uk-margin-bottom">
                <li>
                  <div
                    class="uk-position-relative uk-visible-toggle uk-light"
                    tabindex="-1"
                    uk-slideshow
                  >
                    <ul class="uk-slideshow-items">
                      <li
                        v-for="(img, x) in product.images"
                        :key="`product_${i}_image_${x}`"
                      >
                        <img
                          class="uk-width-1-1"
                          :src="img"
                          style="object-fit:cover;height:170px;"
                          uk-cover
                        />
                      </li>
                    </ul>
                    <div
                      v-show="product.status == 'pending'"
                      class="uk-position-top-left uk-position-small uk-padding-left-remove grey darken-1 white-text uk-text-bold "
                      style="; height: 25px; padding:5px; margin: 10px;"
                    >
                      <p class="uk-text-small" style="padding:0px 6px">
                        <i
                          uk-icon="icon:info; ratio:1"
                          style="color:white;"
                        ></i>
                        OUT OF STOCK
                      </p>
                    </div>
                    <a
                      class="uk-position-center-left uk-position-small uk-hidden-hover"
                      href="#"
                      uk-slidenav-previous
                      uk-slideshow-item="previous"
                    ></a>
                    <a
                      class="uk-position-center-right uk-position-small uk-hidden-hover"
                      href="#"
                      uk-slidenav-next
                      uk-slideshow-item="next"
                    ></a>
                  </div>
                  <div class="uk-padding-small">
                    <h5
                      class="red-text uk-display-block uk-margin-remove-top uk-margin-small-bottom"
                    >
                      {{ product.title }}
                    </h5>
                    <h5 class="red-text uk-margin-remove-top uk-text-bolder">
                      ${{ number_format(product.amount) }}
                    </h5>
                  </div>
                </li>
                <li>
                  <p class="uk-text-small uk-padding-small">
                    {{ product.description }}
                  </p>
                </li>
              </ul>

              <div
                class="uk-text-small uk-flex uk-flex-center uk-position-bottom uk-margin-small-bottom"
                uk-grid
              >
                <div class="uk-width-2-3">
                  <button
                    type="button"
                    :disabled="product.status == 'pending'"
                    @click="add_to_cart(product)"
                    class="uk-button uk-button-small uk-button-primary uk-padding-remove-horizontal uk-border-rounded uk-text-bold uk-text-truncate uk-margin-remove uk-width-1-1 uk-align-center"
                  >
                    <i uk-icon="icon:cart"></i>
                    Add To Cart
                  </button>
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
</template>
<script>
import { mapGetters } from "vuex";
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
  computed: {
    ...mapGetters({
      cart_item_count: "retrieveCartItemsCount",
      cart_items: "retrieveCartItems"
    })
  },
  methods: {
    add_to_cart(item) {
      this.$store.dispatch("addCartItem", {
        name: item.title,
        amount: item.amount,
        id: item.id,
        quantity: 1,
        image: item.images[0]
      });
    },
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
          this.loading = !this.loading;
        })
        .catch(err => {
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
    },
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

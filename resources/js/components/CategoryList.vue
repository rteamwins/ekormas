<template>
  <div class="uk-container uk-padding-remove uk-margin-bottom">
    <div class="uk-margin-large-bottom" uk-grid>
      <div class="uk-width-1-1">
        <div
          class="uk-card uk-card-default uk-padding-remove uk-border-rounded"
        >
          <div class="uk-card-header uk-padding-small">
            <h2 class="uk-h2 uk-margin-remove-bottom uk-text-bolder">
              CATEGORIES
            </h2>
            <p class="uk-margin-remove-top">
              All Categories
            </p>
          </div>
          <div class="uk-card-body uk-padding-remove">
            <a
              href="#"
              @click="new_category()"
              class="uk-button uk-button-small uk-position-top-right uk-background-primary white-text"
              uk-icon="plus"
              >New Category</a
            >
            <table
              class="uk-table uk-table-small uk-table-striped uk-table-middle uk-table-responsive uk-table-divider"
            >
              <thead>
                <tr>
                  <th>#</th>
                  <th>NAME</th>
                  <th>PRODUCT COUNT</th>
                  <th>DATE</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody v-if="Object.keys(categories).length > 0">
                <tr v-for="(category, i) in categories" :key="`category_${i}`">
                  <td>
                    <span class="uk-hidden@m uk-text-bold">#: </span>
                    {{ i + 1 }}
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Name: </span>
                    <span>
                      {{ category.name }}
                    </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold"
                      >Product Count:
                    </span>
                    <span>
                      {{ category.products_count }}
                    </span>
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Date: </span>
                    {{ moment(category.created_at).fromNow() }}
                  </td>
                  <td>
                    <span class="uk-hidden@m uk-text-bold">Action: </span>
                    <span
                      title="Edit category"
                      style="cursor:pointer;"
                      @click="edit_category(category.id, i)"
                      class="uk-icon-button blue-text"
                      uk-icon="file-edit"
                    ></span>
                    <!-- <span
                      title="Delete category"
                      style="cursor:pointer;"
                      @click="delete_category(category.id)"
                      class="uk-icon-button red-text"
                      uk-icon="trash"
                    ></span> -->
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
              v-show="category_pagination_data.page_count > 1"
              class="uk-flex-center"
              uk-margin
            >
              <paginate
                v-model="category_pagination_data.current_page"
                :page-count="category_pagination_data.page_count"
                :page-range="3"
                :margin-pages="2"
                :prev-text="'<span uk-pagination-previous></span>'"
                :next-text="'<span uk-pagination-next></span>'"
                :container-class="'uk-pagination uk-flex-center'"
                :active-class="'uk-active'"
                :disable-class="'uk-disabled'"
                :click-handler="category_page_swap"
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
      categories: [],
      base_url: window.location.origin,
      category_pagination_data: {
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
        .get(`${this.base_url}/api/category/list?page=${page}`)
        .then(res => {
          this.categories = res.data.data.map(x => ({ ...x, show: false }));
          this.load_category_pagination_data(
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
    load_category_pagination_data(last_page, current_page, total_records) {
      this.category_pagination_data = {
        page_count: last_page,
        current_page: current_page,
        record_count: total_records
      };
    },
    category_page_swap(page = this.category_pagination_data.current_page) {
      if (page > this.category_pagination_data.page_count || page < 1) {
        let page = 1;
      }
      this.load_data(page);
      return;
    },
    delete_category(category_id) {
      let current_page = this.category_pagination_data.current_page;
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
              .get(
                `${window.location.origin}/api/category/delete/${category_id}`
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
    edit_category(category_id, i) {
      let current_page = this.category_pagination_data.current_page;
      let category_name = this.categories[i].name;
      const axios_config = {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      };

      this.$swal.fire({
        title: "Edit This category Message?",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Edit",
        cancelButtonText: "Cancel",
        input: "text",
        inputPlaceholder: "Category Name",
        inputValue: category_name,
        showLoaderOnConfirm: true,
        preConfirm: name => {
          let category_data = {
            name: name
          };
          return axios
            .post(
              `${window.location.origin}/api/category/update/${category_id}`,
              category_data,
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
    new_category() {
      let current_page = this.category_pagination_data.current_page;
      const axios_config = {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      };

      this.$swal.fire({
        title: "Create New category",
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: "Create",
        cancelButtonText: "Cancel",
        input: "text",
        inputPlaceholder: "Category Name",
        inputValue: "",
        showLoaderOnConfirm: true,
        preConfirm: name => {
          let category_data = {
            name: name
          };
          return axios
            .post(
              `${window.location.origin}/api/category/new`,
              category_data,
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
    init_categories: {
      required: false,
      type: Array,
      default: null
    }
  }
};
</script>

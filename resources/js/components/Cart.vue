<template>
  <div>
    <a v-show="cart_item_count > 0" class="uk-box-shadow-large" href="#cart_details" uk-toggle>
      <div
        class="uk-card uk-card-default uk-background-primary
              uk-margin-small uk-card-body uk-box-shadow-medium
              uk-padding-small uk-border-pill
              uk-position-fixed uk-position-bottom-right uk-position-z-index"
      >
        <span class="white-text" uk-icon="icon:cart;ratio:1.5"></span>
        <div class="uk-position-top-right uk-label">{{ cart_item_count }}</div>
      </div>
    </a>
    <div id="cart_details" uk-modal>
      <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
          <h2 class="uk-modal-title">Cart</h2>
        </div>

        <div class="uk-modal-body" style="padding:5px;" uk-overflow-auto>
          <table
            class="uk-width-1-1 uk-table uk-table-small uk-table-middle uk-table-divider uk-margin-remove"
          >
            <tbody class="uk-text-small">
              <tr v-for="(item, y) in cart_items" class="uk-width-1-1" :key="'cart_item_' + y">
                <td class="cart_table_data">
                  <img
                    :src="item.image"
                    :alt="item.title"
                    class="cart_thumb"
                  />
                </td>
                <td class="cart_table_data uk-width-1-1">
                  <div class="uk-width-1-1">
                  <p
                    class="uk-text-bold uk-width-1-1 uk-text-truncate uk-margin-remove-vertical"
                  >
                    {{ item.name }}
                  </p>
                  <p class=" uk-margin-remove-vertical">
                    <span class="uk-text-bold"
                      >${{ number_format(item.amount * item.quantity) }}</span
                    >
                    <span class="uk-text-bold">Qty:</span> {{ item.quantity }} <ul class="uk-iconnav uk-text-right">
                    <li>
                      <a
                      v-show="item.quantity < 10 && item.quantity > 0"
                        @click="increase_qty(item.id)"
                        href="#"
                        class="green-text"
                        uk-icon="icon: plus"
                      ></a>
                    </li>
                    <li>
                      <a
                      v-show="item.quantity > 1 "
                        @click="reduce_qty(item.id)"
                        href="#"
                        class="orange-text"
                        uk-icon="icon: minus"
                      ></a>
                    </li>
                    <li>
                      <a
                        @click="delete_item(item.id)"
                        href="#"
                        class="red-text"
                        uk-icon="icon: trash"
                      ></a>
                    </li>
                  </ul>
                  </p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="uk-modal-footer uk-text-right">
          <a v-if="cart_item_count > 0" class="uk-button uk-button-primary" href="#checkout_details" uk-toggle>
            Checkout |
            <span class="uk-text-bold">${{ number_format(total_price) }}</span>
            <span uk-icon="arrow-right"></span>
          </a>
          <button v-else class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
        </div>
      </div>
    </div>
    <div id="checkout_details" uk-modal>
      <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
          <h2 class="uk-modal-title">Checkout</h2>
        </div>

        <div class="uk-modal-body" style="padding:5px;" uk-overflow-auto>
          <div class="uk-margin">
              <label for="intending_state" class="uk-form-label">
                State
              </label>
              <div class="uk-form-control">
                <select v-model="sel_state" class="uk-select" id="intending_state" name="intending_state">
                  <option value="">Select State</option>
                  <option v-for="(state) in states" :key="state.id+'_state'"  :value="state.id">{{state.name}}</option>
                </select>
              </div>
            </div>
            <div class="uk-margin">
              <label for="intending_lga" class="uk-form-label">
                Lga
              </label>
              <div class="uk-form-control">
                <select class="uk-select" v-model="sel_lga" id="intending_lga" name="intending_lga">
                  <option value="">Select Lga</option>
                <option v-for="(lga) in sel_state_lga" :key="lga.id+'_lga'"  :value="lga.id">{{lga.name}}</option>
                </select>
              </div>
            </div>
            <div class="uk-margin">
              <label for="address" class="uk-form-label">
                Physical address
              </label>
              <div class="uk-form-control">
                <textarea v-model="address" id="address" class="uk-textarea" name="address"
                  required></textarea>
              </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
          <a :disabled="loading" @click="checkout()" class="uk-button uk-button-primary" href="#">
            Checkout |
            <span class="uk-text-bold">${{ number_format(total_price) }}</span>
            <span uk-icon="arrow-right"></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      sel_state: '',
      sel_lga: '',
      address: '',
      loading: false,
      axios_config: {
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      },
      y:["quantity","id"],
    };
  },
  computed: {
     ...mapGetters({
      total_price: "retrieveCartItemTotalPrice",
      cart_items: "retrieveCartItems",
      cart_item_count: "retrieveCartItemsCount",
    }),
    sel_state_lga(){
      this.sel_lga =''
      return this.lgas.filter(x=>x.state_id === this.sel_state);
    }
  },
  methods: {
    number_format(x) {
      return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    },
    reduce_qty(x) {
      this.$store.commit('reduceCartItemQuantity',x)
    },
    increase_qty(x) {
      this.$store.commit('increaseCartItemQuantity',x)
    },
    delete_item(x) {
      this.$store.commit('removeCartItem',x)
    },
    checkout(){
      this.loading = !this.loading
      const {sel_lga,sel_state,address, y ,axios_config} = this

      if(sel_lga && sel_state && address){
        if(window.Laravel.userId){
          const form_data  = {
        state: sel_state,
        lga: sel_lga,
        address: address,
        cart_items: this.cart_items.map(({name,
        amount,
        image,...y})=>y)
      }
    console.log(form_data)
    axios.post(
      `${window.location.origin}/api/order/new`,
      form_data,axios_config
    ).then(res=>{
      this.$swal.fire({
                title: `Success: ${res.statusText}`,
                text: `${res.data.message}`,
                icon: "success"
              });
              setTimeout(() => {
                window.location.href = res.data.payment_url
              }, 2000);
    }).catch(err => {
      if(err.response.code == 401){
this.$swal.fire({
                title: "Not Logged In",
                icon: "error",
                text: 'Please login first before proceeding'
              });
      }else{
              this.$swal.fire({
                title: "Failed: " + `${err.response.statusText}`,
                icon: "error",
                text: `${err.response.data.message}`
            });}
              });
        }else{
          this.$swal.fire({
                title: "Not Logged In",
                icon: "error",
                text: 'Please login first before proceeding'
              });
        }

        }else{
          this.$swal.fire({
                  title: 'All Field Required',
                  text: 'One or more fields is empty',
                  icon: "error"
                });
              return

      }
      this.loading = !this.loading
    }

  },
  props: {
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
<style scoped>
.cart_thumb{
  width: 50px !important;
    height: 50px !important;
    min-width: 50px !important;
    min-height: 50px !important;
    max-width: 50px !important;
    max-height: 50px !important;
}
.cart_table_data{
padding:5px ;
}
</style>

import Vue from "vue";
import Vuex from "vuex";
import VuexPersist from "vuex-persist";

const vuexLocal = new VuexPersist({
  key: "ekormas_local_store",
  storage: window.localStorage,
  reducer: state => ({
    cart_items: state.cart_items
  })
});

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    cart_items: []
  },

  getters: {
    retrieveCartItems: state => {
      return state.cart_items;
    },
    retrieveCartItemsCount: state => {
      return state.cart_items.length;
    },
    retrieveCartItemTotalPrice: state => {
      let x = 0;
      state.cart_items.map(y => {
        x += y.quantity * y.amount;
      });
      return x;
    }
  },

  mutations: {
    addCartItem(state, data) {
      state.cart_items = Object.values(
        [...state.cart_items, data].reduce(
          (acc, { id, name, amount, image, quantity }) => {
            acc[id] = {
              id,
              quantity: (acc[id] ? acc[id].quantity : 0) + quantity,
              amount,
              image,
              name
            };
            return acc;
          },
          {}
        )
      );
    },
    removeCartItem(state, item_id) {
      state.cart_items = state.cart_items.filter(v => v.id !== item_id);
    },
    reduceCartItemQuantity(state, item_id) {
      state.cart_items.map(v => {
        if (v.id === item_id) {
          v.quantity -= 1;
        }
      });
    },
    increaseCartItemQuantity(state, item_id) {
      state.cart_items.map(v => {
        if (v.id === item_id) {
          v.quantity += 1;
        }
      });
    }
  },
  actions: {
    addCartItem(context, data) {
      context.commit("addCartItem", data);
    },
    removeCartItem(context, item_id) {
      context.commit("removeCartItem", item_id);
    },
    reduceCartItemQuantity(context, item_id) {
      context.commit("reduceCartItemQuantity", item_id);
    },
    increaseCartItemQuantity(context, item_id) {
      context.commit("increaseCartItemQuantity", item_id);
    }
  },
  modules: {},
  plugins: [vuexLocal.plugin]
});

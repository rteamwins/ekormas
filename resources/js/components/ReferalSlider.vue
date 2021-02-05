<template>
  <div>
    <div
      style="overflow-x: auto;height:60vh;"
      class="uk-width-1-1 uk-text-center"
      id="ref_root"
    >
      <vue2-org-tree
        name="Referals"
        :data="data"
        :props="tree_render_config"
        :horizontal="horizontal"
        :collapsable="collapsable"
        :label-width="labelWidth"
        :label-class-name="labelClassName"
        :render-content="renderContent"
        @on-expand="onExpand"
      />
    </div>
  </div>
</template>
<script>
import Vue2OrgTree from "vue2-org-tree";
import RefNode from "./RefNode";
export default {
  data() {
    return {
      loading: false,
      data: {},
      labelWidth: 155,
      horizontal: false,
      collapsable: true,
      expandAll: true,
      tree_render_config: {
        label: "name",
        children: "children",
        expand: "expand"
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
      }),
      base_url: window.location.origin
    };
  },
  methods: {
    load_data(id = this.user_id) {
      this.loading = !this.loading;
      axios
        .get(`${this.base_url}/api/user/referal/tree_data/for/${id}`)
        .then(res => {
          this.data = { ...res.data };
          this.toggleExpand(this.data, this.expandAll);
          this.Toast.fire({
            icon: "success",
            title: "Data loaded..."
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
    labelClassName: function(data) {
      return "clickable-node";
    },
    renderContent: function(h, data) {
      return h(RefNode, {
        props: {
          name: data.name,
          phone: data.phone,
          username: this.user_name,
          placement_id: data.placement_id,
          canAcceptChild: data.children
            ? this.checkCanAcceptChild(data.children)
            : true,
          isDownline: data.referer == this.user_id
        }
      });
    },
    onExpand: function(e, data) {
      if ("expand" in data) {
        data.expand = !data.expand;

        if (!data.expand && data.children) {
          this.collapse(data.children);
        }
      } else {
        this.$set(data, "expand", true);
      }
    },
    onNodeClick: function(e, data) {
      console.log("onNodeClick: %o", data);
      this.$set(data, "selectedKey", !data.selectedKey);
    },
    collapse: function(list) {
      var _this = this;
      list.forEach(function(child) {
        if (child.expand) {
          child.expand = false;
        }

        child.children && _this.collapse(child.children);
      });
    },
    expandChange: function() {
      this.toggleExpand(this.data, this.expandAll);
    },
    checkCanAcceptChild(data) {
      if (Array.isArray(data)) {
        if (data.length < 2) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    },
    toggleExpand: function(data, val) {
      var _this = this;
      if (Array.isArray(data)) {
        data.forEach(function(item) {
          _this.$set(item, "expand", val);
          if (item.children) {
            _this.toggleExpand(item.children, val);
          }
        });
      } else {
        this.$set(data, "expand", val);
        if (data.children) {
          _this.toggleExpand(data.children, val);
        }
      }
    }
  },
  created() {
    this.load_data();
  },
  props: {
    user_id: {
      required: true,
      type: Number
    },
    user_name: {
      required: true,
      type: String
    }
  },
  components: {
    Vue2OrgTree,
    RefNode
  }
};
</script>
<style scoped>
@import "~vue2-org-tree/dist/style.css";
#ref_root /deep/ .org-tree-node-label .org-tree-node-label-inner {
  padding: 2px;
}
</style>

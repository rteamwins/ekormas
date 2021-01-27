<template>
  <div class="uk-padding-remove green-text">
    <div class="uk-flex uk-flex-center">
      <div class="uk-width-2-5">
        <img
          class="uk-animation-stroke uk-animation-reverse"
          style="height:65px;width:75px;object-fit:cover;"
          :src="`${base_url}/images/misc/default_avatar.svg`"
          alt="User profile picture"
          uk-svg="stroke-animation: true"
        />
      </div>
      <div class="uk-width-3-5">
        <ul class="uk-list uk-list-collapse uk-margin-remove">
          <li class="uk-text-small uk-text-bold uk-text-truncate">
            {{ name }}
          </li>
          <li class="uk-text-bold" style="font-size:12px;">{{ phone }}</li>
          <!-- <li class="uk-text-small uk-text-bold">
            level {{ Math.floor(level / 2) }}
          </li> -->
          <li
            v-show="canAcceptChild"
            class="uk-text-small"
            title="Copy Placement Link"
            style="cursor:pointer;font-size:12px;"
            @click="copyValueToClipboard()"
            uk-icon="ratio:.8;icon:copy"
          >
            Copy Link
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      show_image: false,
      base_url: window.location.origin,
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
  props: {
    name: { type: String, required: true },
    phone: { type: String, required: true },
    username: { type: String, required: true },
    placement_id: { type: Number, required: true },
    canAcceptChild: { type: Boolean, required: true }
  },
  methods: {
    copyValueToClipboard() {
      let tt = this.Toast;
      navigator.clipboard
        .writeText(
          `${this.base_url}/register/ref/${this.username}/pos/${this.placement_id}`
        )
        .then(
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
    }
  }
};
</script>

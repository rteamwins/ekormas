<template>
  <div
    class="uk-margin uk-width-1-1 uk-width-2-3@s uk-width-1-2@m  uk-align-center"
  >
    <label for="funding_kyc_code" class="uk-form-label">
      KYC Code *
    </label>
    <div class="">
      <qrcode-stream
        :camera="camera"
        :torch="torchActive"
        class="uk-margin-small-bottom kyc_scanner_box"
        v-if="!scanner_destroyed"
        @decode="onDecode"
        @init="onInit"
      >
        <span
          style="position: absolute;left: 10px;top: 10px;cursor:pointer;"
          @click="switchCamera"
          class="uk-icon uk-text-bolder bg-transparent white-text"
          uk-icon="refresh"
          title="Switch camera"
        ></span>
        <span
          style="position: absolute;right: 10px;top: 10px;cursor:pointer;"
          @click="torchActive = !torchActive"
          :disabled="torchNotSupported"
          class="uk-icon uk-text-bolder bg-transparent white-text"
          uk-icon="search"
          title="toggle torch"
        ></span>
        <div class="uk-text-center  uk-text-bold green-text" v-if="loading">
          Scanner<br />
          Loading...
        </div>
      </qrcode-stream>
    </div>
    <div class="uk-inline uk-width-1-1">
      <button
        v-show="scanner_destroyed"
        type="button"
        @click.prevent="start_scanner"
        class="uk-form-icon uk-form-icon-flip"
        uk-icon="icon: camera"
      ></button>
      <button
        v-show="!scanner_destroyed"
        type="button"
        @click.prevent="stop_scanner"
        class="uk-form-icon uk-form-icon-flip"
        uk-icon="icon: close"
      ></button>
      <input
        autocomplete="off"
        ref="funding_kyc_code"
        :class="[
          'uk-input',
          'uk-border-rounded',
          { 'uk-form-danger': error.status }
        ]"
        name="funding_kyc_code"
        id="funding_kyc_code"
        type="text"
        v-model="funding_kyc_code"
        required
      />
    </div>
    <span v-show="error.status" class="uk-text-danger">{{
      error.message
    }}</span>
  </div>
</template>
<script>
import { QrcodeStream } from "vue-qrcode-reader";
export default {
  data() {
    return {
      torchActive: false,
      torchNotSupported: false,
      camera: "auto",
      kyc_code_regex: new RegExp("^[a-zA-Z0-9]{22}$"),
      loading: false,
      scanner_destroyed: true,
      error: { status: false, message: "" },
      funding_kyc_code: "",
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
  methods: {
    switchCamera() {
      switch (this.camera) {
        case "auto":
          this.camera = "front";
          break;
        case "front":
          this.camera = "auto";
          break;
      }
    },

    onDecode(result) {
      if (this.kyc_code_regex.test(result) === false) {
        this.error.message = "Invalid KYC Code";
        this.error.status = true;
        this.Toast.fire({
          icon: "error",
          title: "Invalid KYC Code"
        });
      } else {
        this.funding_kyc_code = result;
        this.error.message = "";
        this.error.status = false;
        this.stop_scanner();
        this.Toast.fire({
          icon: "success",
          title: "KYC Code Scan Complete"
        });
      }
    },
    copyValueToClipboard(x) {
      console.log(x);
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
    view_kyc_qrcode(kyc) {
      this.address_qrcode_value = kyc.addess;
      this.code_qrcode_value = kyc.code;
      let addressQR = this.$refs.address_qrcode_box.innerHTML;
      let codeQR = this.$refs.code_qrcode_box.innerHTML;
      this.$swal
        .mixin({
          reverseButtons: true,
          confirmButtonText: "Next",
          showCancelButton: true,
          progressSteps: ["1", "2"]
        })
        .queue([
          {
            title: "KYC Address",
            html: addressQR
          },
          {
            title: "KYC Code",
            html: codeQR
          }
        ])
        .then(result => {
          if (result.value) {
            this.$swal.fire({
              icon: "info",
              title: "Information",
              text:
                "Do not share your KYC code with anyone except in events of financial exchange.",
              confirmButtonText: "Okay!"
            });
          }
        });
    },
    async onInit(promise) {
      this.loading = true;
      try {
        const { capabilities } = await promise;
        this.torchNotSupported = !capabilities.torch;
      } catch (error) {
        const triedFrontCamera = this.camera === "front";
        const triedRearCamera = this.camera === "auto";

        if (error.name === "NotAllowedError") {
          this.error.message = "Grant camera access permisson";
          this.error.status = true;
        } else if (error.name === "NotFoundError") {
          this.error.message = "Nocamera on this device";
          this.error.status = true;
        } else if (error.name === "NotSupportedError") {
          this.error.message = "Secure context required(HTTPS)";
          this.error.status = true;
        } else if (error.name === "NotReadableError") {
          this.error.message = "Is the camera already in use?";
          this.error.status = true;
        } else if (error.name === "OverconstrainedError") {
          if (triedRearCamera) {
            this.error.message = "No Rear Camera";
            this.error.status = true;
          } else if (triedFrontCamera) {
            this.error.message = "No Front Camera";
            this.error.status = true;
          } else {
            this.error.message = "Available cameras are not suitable";
            this.error.status = true;
          }
        } else if (error.name === "StreamApiNotSupportedError") {
          this.error.message = "You Browser/device does not support scanning";
          this.error.status = true;
        }
        this.Toast.fire({
          icon: "error",
          title: this.error.message
        });
      } finally {
        this.loading = false;
      }
    },
    async start_scanner() {
      this.scanner_destroyed = true;
      this.$refs.funding_kyc_code.setAttribute("readonly", "true");
      await this.$nextTick();
      this.scanner_destroyed = false;
    },
    async stop_scanner() {
      this.scanner_destroyed = true;
      this.$refs.funding_kyc_code.removeAttribute("readonly");
      await this.$nextTick();
      this.scanner_destroyed = true;
    }
  },
  created() {
    if (this.old_value !== "") {
      this.funding_kyc_code = this.old_value;
    }
    if (this.old_error !== null) {
      this.error = { ...this.old_error };
    }
  },
  props: {
    old_value: {
      required: false,
      type: String,
      default: ""
    },
    old_error: {
      required: false,
      type: Object,
      default: null
    }
  },
  components: {
    QrcodeStream
  }
};
</script>
<style lang="css" scoped>
.kyc_scanner_box /deep/ .qrcode-stream-camera {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border-radius: 5px;
}
</style>

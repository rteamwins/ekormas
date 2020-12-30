<template>
  <div>
    <div
      id="ref_top"
      style="overflow-x: auto;"
      class="uk-width-1-1 uk-container uk-flex uk-flex-inline uk-margin-small-bottom"
    >
      <div
        v-for="n in 2**((level*2)-1)"
        :key="`t_ref_${n}`"
        class="uk-padding-remove grey lighten-1 uk-border-rounded uk-margin-small-right"
      >
        <div class="ref_card uk-grid-collapse white-text" uk-grid>
          <div class="uk-width-2-5">
            <img
              class="uk-animation-stroke uk-animation-reverse"
              style="height:100%;width:100%;object-fit:cover;"
              :src="`${base_url}/images/misc/default_avatar_white.svg`"
              alt="User profile picture"
              uk-svg="stroke-animation: true"
            />
          </div>
          <div class="uk-width-3-5">
            <ul class="uk-list uk-list-collapse">
              <li class="uk-text-small uk-text-bold">John Doe</li>
              <li class="uk-text-small uk-text-bold">01821383293</li>
              <li class="uk-text-small uk-text-bold">#{{ n }}</li>
              <li class="uk-text-small uk-text-bold">level {{ level }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div
      id="ref_bottom"
      style="overflow-x: auto;"
      class="uk-width-1-1 uk-container uk-flex uk-flex-inline"
    >
      <div
        v-for="n in 2**(level*2)"
        :key="`b_ref_${n}`"
        class="uk-padding-remove grey lighten-1 uk-border-rounded uk-margin-small-right"
      >
        <div class="ref_card uk-grid-collapse white-text" uk-grid>
          <div class="uk-width-2-5">
            <img
              class="uk-animation-stroke uk-animation-reverse"
              style="height:100%;width:100%;object-fit:cover;"
              :src="`${base_url}/images/misc/default_avatar_white.svg`"
              alt="User profile picture"
              uk-svg="stroke-animation: true"
            />
          </div>
          <div class="uk-width-3-5">
            <ul class="uk-list uk-list-collapse">
              <li class="uk-text-small uk-text-bold">John Doe</li>
              <li class="uk-text-small uk-text-bold">01821383293</li>
              <li class="uk-text-small uk-text-bold">#{{ n }}</li>
              <li class="uk-text-small uk-text-bold">level {{ level }}</li>
            </ul>
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
      top_ref: [],
      bottom_ref: [],
      level: 1,
      base_url: window.location.origin
    };
  }
};
</script>

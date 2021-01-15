<template>
  <div>
    <apexchart
      width="100%"
      height="250"
      type="candlestick"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
</template>
<script>
export default {
  data() {
    return {
      series: [
        {
          data: []
        }
      ],
      chartOptions: {
        chart: {
          type: "candlestick"
        },

        xaxis: {
          type: "datetime"
        },
        yaxis: {
          tooltip: {
            enabled: true
          },
          tickAmount: 6,
          decimalsInFloat: 2,
          labels: {
            show: true
          }
        },
        title: {
          text: "TGLM Live Market",
          floating: true,
          offsetY: 20,
          align: "center",
          style: {
            color: "#304758"
          }
        }
      }
    };
  },
  methods: {},
  created() {
    this.series = [...this.chartdata.series];
  },
  props: {
    chartdata: {
      type: Object,
      default: null
    }
  }
};
</script>

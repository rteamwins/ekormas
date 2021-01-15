<template>
  <div>
    <apexchart
      width="100%"
      height="250"
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
          name: "Trade Profits",
          data: []
        }
      ],
      chartOptions: {
        colors: ["#4caf50"],
        chart: {
          type: "bar"
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: "top" // top, center, bottom
            }
            // endingShape: "rounded"
          }
        },

        dataLabels: {
          enabled: false,
          formatter: function(val) {
            return "$" + val;
          },
          offsetY: -20,
          style: {
            fontSize: "12px",
            colors: ["#304758"]
          }
        },
        stroke: {
          curve: "smooth"
        },
        xaxis: {
          categories: [],
          position: "top",
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: "gradient",
              gradient: {
                colorFrom: "#d3f1ed",
                colorTo: "#a8ded7",
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5
              }
            }
          },
          tooltip: {
            enabled: true
          },
          labels: {
            show: false
          }
        },
        yaxis: {
          axisBorder: {
            show: true
          },
          axisTicks: {
            show: true
          },
          labels: {
            show: true
          }
        },
        title: {
          text: "Today's Trade Profit Earnings",
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
    this.chartOptions.xaxis.categories = [...this.chartdata.labels];
  },
  props: {
    chartdata: {
      type: Object,
      default: null
    }
  }
};
</script>

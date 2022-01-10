<template>
  <component
    ref="chart"
    class='va-chart'
    :is="chartComponent"
    :chartOptions="options"
    :chart-data="data"
  />
</template>

<script>
import PieChart from './chart-types/PieChart'
import VerticalBarChart from './chart-types/VerticalBarChart'
import LineChart from './chart-types/LineChart'
import { chartTypesMap } from './VaChartConfigs'

export default {
  name: 'va-chart',
  props: {
    data: {},
    options: {},
    type: {
      validator (type) {
        return type in chartTypesMap
      },
    },
  },
  components: {
    PieChart,
    LineChart,
    VerticalBarChart,
  },
  computed: {
    chartComponent () {
      return chartTypesMap[this.type]
    },
  },
  methods: {
    refresh() {
      this.$refs.chart.refresh()
    },
  }
}
</script>

<style lang='scss'>
.va-chart {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  > * {
    height: 100%;
    width: 100%;
  }

  canvas {
    width: 100%;
    height: auto;
    min-height: 320px;
  }
}
</style>

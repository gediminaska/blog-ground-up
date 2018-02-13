<template src="./ActivityGraph.html"></template>

<script>
    import LineChart from './LineChart'
    import PieChart from './PieChart'
    import BarChart from './BarChart'
    import BarChart2 from './BarChart2'
    export default {
        components: {
            'line-chart' : LineChart,
            'pie-chart' : PieChart,
            'bar-chart' : BarChart,
            'bar-chart2' : BarChart2,
        }
    }
</script>
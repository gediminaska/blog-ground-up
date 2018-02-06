import {Line} from 'vue-chartjs'

export default {
    extends: Line,
    mounted () {
        this.renderChart({
            labels: ['jan', 'feb', 'mar', 'apr', 'may', 'jun'],
            datasets: [
                {label: 'My activities', backgroundColor: '#dd4b39', data: [40, 39, 45, 22, 14, 5]}
            ]
        }, {responsive: true, maintainAspectRatio: false})
    }
}
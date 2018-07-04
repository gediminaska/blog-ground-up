import axios from 'axios'
import {Line} from 'vue-chartjs'

export default {
    extends: Line,
    mounted () {
        let vm = this
        axios.get('https://' + window.location.hostname +'/api/dashboard/activity/posts', {
            params: {
                api_token: vm.api_token
            }
        })
            .catch(function (error) {
                alert(error);
            })
            .then(response => {
                this.rows = response.data.data.rows;
                this.labels = response.data.data.labels;
                this.setGraph();
            })
    },

    data () {
        return {
            rows: [],
            labels: [],
            api_token: this.$root.api_token,

        }
    },
    methods: {
        setGraph() {
            this.renderChart({
                labels: this.labels,
                datasets: [
                    {label: 'Number of posts', backgroundColor: '#dd4b39', data: this.rows}
                ]
            }, {responsive: true, maintainAspectRatio: false, scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }})
        }
    }
}


import axios from 'axios'
import {Bar} from 'vue-chartjs'

export default {
    extends: Bar,
    mounted () {
        let vm = this
        axios.get('http://' + window.location.hostname +'/api/dashboard/activity/comments', {
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
                this.setGraph()
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
                    {label: 'Number of comments', backgroundColor: '#3097D1', data: this.rows}
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
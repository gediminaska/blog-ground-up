import axios from 'axios'
import {Bar} from 'vue-chartjs'

export default {
    extends: Bar,
    mounted () {
        let vm = this
        axios.get('http://' + window.location.hostname +'/api/dashboard/activity/users', {
            params: {
                api_token: vm.api_token
            }
        })
            .then(response => {
                console.log(response.data);
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
                    {label: 'Number of posts', backgroundColor: '#8eb4cb', data: this.rows}
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
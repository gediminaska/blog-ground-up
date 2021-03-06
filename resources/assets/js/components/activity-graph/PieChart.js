window.Laravel = { csrfToken: '{{ csrf_token() }}' }



import axios from 'axios'
import {Doughnut} from 'vue-chartjs'

export default {
    extends: Doughnut,
    mounted () {
        let vm = this
        axios.get('https://' + window.location.hostname +'/api/dashboard/activity/categories', {
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
                    {label: 'Number of posts', backgroundColor: ['#3097D1', '#8eb4cb', '#2ab27b', '#cbb956', '#bf5329'], data: this.rows}
                ]
            }, {responsive: true, maintainAspectRatio: false})
        }
    }
}
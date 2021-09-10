<script>
import {Line} from "vue-chartjs"


export default{
    extends : Line,
    props: {
        label: {
            type : String
        },
        chartData: {
            type: Array
        },

        options:{
            type : Object
        }
    },


    mounted(){

        const dates = this.chartData.map(d=> d.date);
        const high = this.chartData.map(d=> d.high);
        const low = this.chartData.map(d=> d.low);
        const open = this.chartData.map(d=> d.open);
        this.renderChart({
            labels: dates,
            datasets:[
                {
                    label: "high value",
                    data: high,
                    borderColor: 'green',
                    backgroundColor: 'transparent'
                },
                {
                    label: "low value",
                    data: low,
                    borderColor: '#FC2525',
                    backgroundColor: 'transparent'
                },
                {
                    label: "open value",
                    data: open,
                    borderColor: '#3c84b7',
                    backgroundColor: 'transparent'
                },

            ],

        }, {
                responsive:true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                    ticks: {
                        min: 0,
                        max: Math.ceil(this.chartData.reduce((prev, current) => (prev.high > current.high) ? prev : current).high) + Math.ceil(this.chartData.reduce((prev, current) => (prev.high > current.high) ? prev : current).high)/2,
                        stepSize: (Math.ceil(this.chartData.reduce((prev, current) => (prev.high > current.high) ? prev : current).high) + Math.ceil(this.chartData.reduce((prev, current) => (prev.high < current.high) ? prev : current).low))/6,
                        reverse: false,
                        beginAtZero: true
                    }
                }]
                }
            });
    }
}
</script>
<style>

</style>

<template>
    <h3>
        Statistics: Daily Registered Users
    </h3>
    <div class="stats-chart">
        <canvas id="stat-chart" width="900" height="500"></canvas>
    </div>
</template>

<script>

    //User Store
    import userStore from '../../stores/user.js';
    import Chart from 'chart.js';

    export default {

        data() {
            return {
                state: {
                    userStore: userStore.state
                },
            };
        },

        computed: {
            stats() {
                return this.state.userStore.stats;
            }
        },

        route: {
            data() {
                this.fetchStats();
            }
        },

        methods: {
            fetchStats() {
                userStore.getStats(
                    (stats) => {
                        var ctx = document.getElementById("stat-chart");
                        let statChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: stats.labels,
                                datasets: [{
                                    label: 'Daily registered users',
                                    data: stats.counts,
                                    fill: false,
                                    borderColor: "#2b90d9",
                                    pointBackgroundColor: "#c17ce4",
                                    pointBorderColor: "#c17ce4",
                                    pointRadius: 5,
                                    pointHitRadius: 5,
                                    pointHoverRadius: 6,
                                }]
                            },
                            options: {
                                maintainAspectRatio: false,

                                scales: {
                                    xAxes: [{
                                        display: false
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
}
);
}
}
}
</script>
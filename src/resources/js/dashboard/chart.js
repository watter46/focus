import Chart from 'chart.js/auto';

window.dashboardSetup = (dataList) => dashboardSetup(dataList);

const dashboardSetup = (dataList) => {
    const ctx = document.getElementById('myChart');
    
    const labels = dataList.map(data => data.date);
    const data   = dataList.map(data => data.time_min);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Development Time',
                data: data,
                borderWidth: 1,
                borderColor: '#155e75',
                backgroundColor: '#22d3ee'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
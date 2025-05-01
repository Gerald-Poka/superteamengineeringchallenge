export function initSalesChart(salesData) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: salesData.map(day => day.day),
            datasets: [
                {
                    label: 'Sales Amount ($)',
                    data: salesData.map(day => day.amount),
                    backgroundColor: 'rgba(217, 119, 6, 0.8)',
                    borderColor: 'rgb(217, 119, 6)',
                    borderWidth: 2,
                    borderRadius: 4,
                    barThickness: 20,
                    yAxisID: 'y'
                },
                {
                    label: 'Products Sold',
                    data: salesData.map(day => day.products_count),
                    backgroundColor: 'rgba(251, 191, 36, 0.4)',
                    borderColor: 'rgb(251, 191, 36)',
                    borderWidth: 2,
                    borderRadius: 4,
                    barThickness: 20,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'figtree',
                            size: 12
                        },
                        color: '#92400E',
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 251, 235, 0.9)',
                    titleColor: '#92400E',
                    bodyColor: '#92400E',
                    bodyFont: {
                        family: 'figtree'
                    },
                    borderColor: 'rgba(217, 119, 6, 0.2)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD'
                                }).format(context.raw);
                            } else {
                                label += context.raw + ' units';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(217, 119, 6, 0.1)'
                    },
                    ticks: {
                        color: '#92400E',
                        font: {
                            family: 'figtree'
                        },
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        color: '#92400E',
                        font: {
                            family: 'figtree'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#92400E',
                        font: {
                            family: 'figtree',
                            weight: 500
                        }
                    }
                }
            }
        }
    });
}
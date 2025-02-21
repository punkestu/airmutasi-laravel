// function generateChart(title, series) {
//   return {
//     series,
//     chart: {
//       type: "line",
//       zoom: {
//         enabled: false,
//       },
//     },
//     dataLabels: {
//       enabled: false,
//     },
//     stroke: {
//       curve: "straight",
//     },
//     title: {
//       text: title,
//       align: "left",
//     },
//     grid: {
//       row: {
//         colors: ["#f3f3f3", "transparent"],
//         opacity: 0.5,
//       },
//     },
//     xaxis: {
//       categories: [
//         "Jan",
//         "Feb",
//         "Mar",
//         "Apr",
//         "May",
//         "Jun",
//         "Jul",
//         "Aug",
//         "Sep",
//       ],
//     },
//   };
// }

function generateBarChart(title, series) {
    return {
        series,
        chart: {
            type: "bar",
        },
        title: {
            text: title,
            align: "left",
            style: {
                fontFamily: "geruduk",
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: "rounded",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: ["Minimal", "Eksisting", "Maksimal"],
        },
        yaxis: {
            title: {
                text: "Jumlah Personel",
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " Orang";
                },
            },
        },
    };
}

function generatePieChart(title, series, labels = []) {
    return {
        series,
        chart: {
            type: "pie",
        },
        labels: labels,
        title: {
            text: title,
            align: "left",
            style: {
                fontFamily: "geruduk",
            },
        },
        responsive: [
            {
                breakpoint: 480,
                options: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        ],
    };
}

function generateRadarChart(title, series, items = ["ATC", "ACO", "AIS", "ATFM", "TAPOR", "ATS System", "CNS", "ESS", "Staff Umum"]) {
    return {
        series,
        chart: {
            height: 350,
            type: "radar",
        },
        title: {
            text: title,
            style: {
                color: "#fff",
                fontWeight: "bold",
            },
        },
        xaxis: {
            categories: items,
            labels: {
                style: {
                    colors: items.map(() => "#fff"),
                },
            },
        },
        yaxis: {
            tickAmount: 3,
            labels: {
                style: {
                    colors: items.map(() => "#ff0"),
                },
            },
        },
        legend: {
            labels: {
                colors: "#fff"
            }
        }
    };
}

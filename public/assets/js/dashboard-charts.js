function GenerusByClassChart(feed) {
    const labels = Object.keys(feed);

    const data = {
        labels: labels,
        datasets: [
            {
                label: "Perempuan",
                data: Object.values(feed).map((val) => val.P),
                backgroundColor: ["rgba(255, 99, 132, 0.2)"],
                borderColor: ["rgb(255, 99, 132)"],
                borderWidth: 2,
                borderSkipped: false,
            },
            {
                label: "Laki Laki",
                data: Object.values(feed).map((val) => val.L),
                backgroundColor: ["rgba(54, 162, 235, 0.2)"],
                borderColor: ["rgb(75, 192, 192)"],
                borderWidth: 2,
                borderSkipped: false,
            },
        ],
    };
    const config = {
        type: "bar",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "Perbandingan Jumlah Generus Berdasarkan Kelas & Jenis Kelamin",
                },
            },
        },
    };

    const ctx = document.getElementById("GenerusByClass").getContext("2d");
    const myChart = new Chart(ctx, config);
}
function GenerusByClassOverallChart(feed) {
    const labels = Object.keys(feed);
    const data = {
        labels: labels,
        datasets: [
            {
                label: "Overall",
                data: Object.values(feed).map((val) => val.total),
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(201, 203, 207, 0.2)",
                ],
                borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)",
                ],
            },
        ],
    };

    const config = {
        type: "pie",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
                title: {
                    display: true,
                    text: "Perbandingan Jumlah Generus Berdasarkan Kelas",
                },
                datalabels: {
                    color: "#444",
                    formatter: (value) => value, // show raw count
                },
            },
        },
        plugins: [ChartDataLabels], // enable datalabels
    };

    const ctx = document
        .getElementById("GenerusByClassOverall")
        .getContext("2d");
    const myChart = new Chart(ctx, config);
}

function GenerusByEducationChart(feed) {
    const labels = Object.keys(feed);
    const data = {
        labels: labels,
        datasets: [
            {
                label: "Overall",
                data: Object.values(feed).map((val) => val),
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(201, 203, 207, 0.2)",
                ],
                borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)",
                ],
            },
        ],
    };
    const config = {
        type: "bar",
        data: data,
        options: {
            indexAxis: "y",
            elements: {
                bar: {
                    borderWidth: 2,
                },
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                    position: "right",
                },
                title: {
                    display: true,
                },
            },
        },
    };
    const ctx = document.getElementById("GenerusByEducation").getContext("2d");
    const myChart = new Chart(ctx, config);
}
function GenerusByJobChart(feed) {
    const labels = Object.keys(feed);
    const data = {
        labels: labels,
        datasets: [
            {
                label: "Overall",
                data: Object.values(feed).map((val) => val),
                backgroundColor: [
                    "rgba(255, 99, 132, 0.2)",
                    "rgba(255, 159, 64, 0.2)",
                    "rgba(255, 205, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(201, 203, 207, 0.2)",
                ],
                borderColor: [
                    "rgb(255, 99, 132)",
                    "rgb(255, 159, 64)",
                    "rgb(255, 205, 86)",
                    "rgb(75, 192, 192)",
                    "rgb(54, 162, 235)",
                    "rgb(153, 102, 255)",
                    "rgb(201, 203, 207)",
                ],
            },
        ],
    };
    const config = {
        type: "pie",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                },
                datalabels: {
                    color: "#444",
                    formatter: (value) => value, // show raw count
                },
            },
        },
        plugins: [ChartDataLabels], // enable datalabels
    };
    const ctx = document.getElementById("GenerusByJob").getContext("2d");
    const myChart = new Chart(ctx, config);
}

$(document).ready(function () {
  new Chart(document.getElementById("lineChart"), {
    type: "line",
    data: {
      labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
      datasets: [
        {
          label: "Ventas",
          data: [1200, 1900, 3000, 2500, 3200, 4000],
          borderColor: "#3b82f6",
          backgroundColor: "rgba(59,130,246,0.1)",
          fill: true,
          tension: 0.4,
        },
      ],
    },
  });

  // BAR CHART
  new Chart(document.getElementById("barChart"), {
    type: "bar",
    data: {
      labels: ["Producto A", "Producto B", "Producto C"],
      datasets: [
        {
          label: "Ventas",
          data: [120, 90, 150],
          backgroundColor: ["#6366f1", "#10b981", "#f59e0b"],
        },
      ],
    },
  });

  // DOUGHNUT CHART
  new Chart(document.getElementById("pieChart"), {
    type: "doughnut",
    data: {
      labels: ["Desktop", "Mobile", "Tablet"],
      datasets: [
        {
          data: [60, 30, 10],
          backgroundColor: ["#3b82f6", "#ef4444", "#14b8a6"],
        },
      ],
    },
  });
});

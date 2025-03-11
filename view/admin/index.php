<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
?>

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row">
  <!-- Info Cards -->
  <div class="col-lg-4 col-md-6">
    <div class="card info-card revenue-card">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-currency-dollar"></i>
        </div>
        <h6 class="mt-3">Revenue</h6>
        <h2>Rs. 8,55,875</h2>
        <a href="#" class="btn btn-custom mt-3" style="background-color: #7ddf64;">View Detailed Report <i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card info-card inventory-card">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-capsule"></i>
        </div>
        <h6 class="mt-3">Medicines Available</h6>
        <h2>298</h2>
        <a href="inventory.php" class="btn btn-custom mt-3" style="background-color: #7ddf64;">Visit Inventory <i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card info-card staff-card">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-people"></i>
        </div>
        <h6 class="mt-3">Staffs on Shift</h6>
        <h2>3</h2>
        <a href="#" class="btn btn-custom mt-3" style="background-color: #7ddf64;">View Staffs <i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Sales Performance</h5>
        <div id="areaChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const series = {
              "monthDataSeries1": {
                "prices": [
                  8107.85,
                  8128.0,
                  8122.9,
                  8165.5,
                  8340.7,
                  8423.7,
                  8423.5,
                  8514.3,
                  8481.85,
                  8487.7,
                  8506.9,
                  8626.2,
                  8668.95,
                  8602.3,
                  8607.55,
                  8512.9,
                  8496.25,
                  8600.65,
                  8881.1,
                  9340.85
                ],
                "dates": [
                  "13 Nov 2017",
                  "14 Nov 2017",
                  "15 Nov 2017",
                  "16 Nov 2017",
                  "17 Nov 2017",
                  "20 Nov 2017",
                  "21 Nov 2017",
                  "22 Nov 2017",
                  "23 Nov 2017",
                  "24 Nov 2017",
                  "27 Nov 2017",
                  "28 Nov 2017",
                  "29 Nov 2017",
                  "30 Nov 2017",
                  "01 Dec 2017",
                  "04 Dec 2017",
                  "05 Dec 2017",
                  "06 Dec 2017",
                  "07 Dec 2017",
                  "08 Dec 2017"
                ]
              },
              "monthDataSeries2": {
                "prices": [
                  8423.7,
                  8423.5,
                  8514.3,
                  8481.85,
                  8487.7,
                  8506.9,
                  8626.2,
                  8668.95,
                  8602.3,
                  8607.55,
                  8512.9,
                  8496.25,
                  8600.65,
                  8881.1,
                  9040.85,
                  8340.7,
                  8165.5,
                  8122.9,
                  8107.85,
                  8128.0
                ],
                "dates": [
                  "13 Nov 2017",
                  "14 Nov 2017",
                  "15 Nov 2017",
                  "16 Nov 2017",
                  "17 Nov 2017",
                  "20 Nov 2017",
                  "21 Nov 2017",
                  "22 Nov 2017",
                  "23 Nov 2017",
                  "24 Nov 2017",
                  "27 Nov 2017",
                  "28 Nov 2017",
                  "29 Nov 2017",
                  "30 Nov 2017",
                  "01 Dec 2017",
                  "04 Dec 2017",
                  "05 Dec 2017",
                  "06 Dec 2017",
                  "07 Dec 2017",
                  "08 Dec 2017"
                ]
              },
              "monthDataSeries3": {
                "prices": [
                  7114.25,
                  7126.6,
                  7116.95,
                  7203.7,
                  7233.75,
                  7451.0,
                  7381.15,
                  7348.95,
                  7347.75,
                  7311.25,
                  7266.4,
                  7253.25,
                  7215.45,
                  7266.35,
                  7315.25,
                  7237.2,
                  7191.4,
                  7238.95,
                  7222.6,
                  7217.9,
                  7359.3,
                  7371.55,
                  7371.15,
                  7469.2,
                  7429.25,
                  7434.65,
                  7451.1,
                  7475.25,
                  7566.25,
                  7556.8,
                  7525.55,
                  7555.45,
                  7560.9,
                  7490.7,
                  7527.6,
                  7551.9,
                  7514.85,
                  7577.95,
                  7592.3,
                  7621.95,
                  7707.95,
                  7859.1,
                  7815.7,
                  7739.0,
                  7778.7,
                  7839.45,
                  7756.45,
                  7669.2,
                  7580.45,
                  7452.85,
                  7617.25,
                  7701.6,
                  7606.8,
                  7620.05,
                  7513.85,
                  7498.45,
                  7575.45,
                  7601.95,
                  7589.1,
                  7525.85,
                  7569.5,
                  7702.5,
                  7812.7,
                  7803.75,
                  7816.3,
                  7851.15,
                  7912.2,
                  7972.8,
                  8145.0,
                  8161.1,
                  8121.05,
                  8071.25,
                  8088.2,
                  8154.45,
                  8148.3,
                  8122.05,
                  8132.65,
                  8074.55,
                  7952.8,
                  7885.55,
                  7733.9,
                  7897.15,
                  7973.15,
                  7888.5,
                  7842.8,
                  7838.4,
                  7909.85,
                  7892.75,
                  7897.75,
                  7820.05,
                  7904.4,
                  7872.2,
                  7847.5,
                  7849.55,
                  7789.6,
                  7736.35,
                  7819.4,
                  7875.35,
                  7871.8,
                  8076.5,
                  8114.8,
                  8193.55,
                  8217.1,
                  8235.05,
                  8215.3,
                  8216.4,
                  8301.55,
                  8235.25,
                  8229.75,
                  8201.95,
                  8164.95,
                  8107.85,
                  8128.0,
                  8122.9,
                  8165.5,
                  8340.7,
                  8423.7,
                  8423.5,
                  8514.3,
                  8481.85,
                  8487.7,
                  8506.9,
                  8626.2
                ],
                "dates": [
                  "02 Jun 2017",
                  "05 Jun 2017",
                  "06 Jun 2017",
                  "07 Jun 2017",
                  "08 Jun 2017",
                  "09 Jun 2017",
                  "12 Jun 2017",
                  "13 Jun 2017",
                  "14 Jun 2017",
                  "15 Jun 2017",
                  "16 Jun 2017",
                  "19 Jun 2017",
                  "20 Jun 2017",
                  "21 Jun 2017",
                  "22 Jun 2017",
                  "23 Jun 2017",
                  "27 Jun 2017",
                  "28 Jun 2017",
                  "29 Jun 2017",
                  "30 Jun 2017",
                  "03 Jul 2017",
                  "04 Jul 2017",
                  "05 Jul 2017",
                  "06 Jul 2017",
                  "07 Jul 2017",
                  "10 Jul 2017",
                  "11 Jul 2017",
                  "12 Jul 2017",
                  "13 Jul 2017",
                  "14 Jul 2017",
                  "17 Jul 2017",
                  "18 Jul 2017",
                  "19 Jul 2017",
                  "20 Jul 2017",
                  "21 Jul 2017",
                  "24 Jul 2017",
                  "25 Jul 2017",
                  "26 Jul 2017",
                  "27 Jul 2017",
                  "28 Jul 2017",
                  "31 Jul 2017",
                  "01 Aug 2017",
                  "02 Aug 2017",
                  "03 Aug 2017",
                  "04 Aug 2017",
                  "07 Aug 2017",
                  "08 Aug 2017",
                  "09 Aug 2017",
                  "10 Aug 2017",
                  "11 Aug 2017",
                  "14 Aug 2017",
                  "16 Aug 2017",
                  "17 Aug 2017",
                  "18 Aug 2017",
                  "21 Aug 2017",
                  "22 Aug 2017",
                  "23 Aug 2017",
                  "24 Aug 2017",
                  "28 Aug 2017",
                  "29 Aug 2017",
                  "30 Aug 2017",
                  "31 Aug 2017",
                  "01 Sep 2017",
                  "04 Sep 2017",
                  "05 Sep 2017",
                  "06 Sep 2017",
                  "07 Sep 2017",
                  "08 Sep 2017",
                  "11 Sep 2017",
                  "12 Sep 2017",
                  "13 Sep 2017",
                  "14 Sep 2017",
                  "15 Sep 2017",
                  "18 Sep 2017",
                  "19 Sep 2017",
                  "20 Sep 2017",
                  "21 Sep 2017",
                  "22 Sep 2017",
                  "25 Sep 2017",
                  "26 Sep 2017",
                  "27 Sep 2017",
                  "28 Sep 2017",
                  "29 Sep 2017",
                  "03 Oct 2017",
                  "04 Oct 2017",
                  "05 Oct 2017",
                  "06 Oct 2017",
                  "09 Oct 2017",
                  "10 Oct 2017",
                  "11 Oct 2017",
                  "12 Oct 2017",
                  "13 Oct 2017",
                  "16 Oct 2017",
                  "17 Oct 2017",
                  "18 Oct 2017",
                  "19 Oct 2017",
                  "23 Oct 2017",
                  "24 Oct 2017",
                  "25 Oct 2017",
                  "26 Oct 2017",
                  "27 Oct 2017",
                  "30 Oct 2017",
                  "31 Oct 2017",
                  "01 Nov 2017",
                  "02 Nov 2017",
                  "03 Nov 2017",
                  "06 Nov 2017",
                  "07 Nov 2017",
                  "08 Nov 2017",
                  "09 Nov 2017",
                  "10 Nov 2017",
                  "13 Nov 2017",
                  "14 Nov 2017",
                  "15 Nov 2017",
                  "16 Nov 2017",
                  "17 Nov 2017",
                  "20 Nov 2017",
                  "21 Nov 2017",
                  "22 Nov 2017",
                  "23 Nov 2017",
                  "24 Nov 2017",
                  "27 Nov 2017",
                  "28 Nov 2017"
                ]
              }
            }
            new ApexCharts(document.querySelector("#areaChart"), {
              series: [{
                name: "STOCK ABC",
                data: series.monthDataSeries1.prices
              }],
              chart: {
                type: 'area',
                height: 350,
                zoom: {
                  enabled: false
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                curve: 'straight'
              },
              subtitle: {
                text: 'Price Movements',
                align: 'left'
              },
              labels: series.monthDataSeries1.dates,
              xaxis: {
                type: 'datetime',
              },
              yaxis: {
                opposite: true
              },
              legend: {
                horizontalAlign: 'left'
              }
            }).render();
          });
        </script>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Calendar</h5>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const calendarElement = document.getElementById('calendar');
    const currentDate = new Date();
    const currentDay = currentDate.getDate();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    let calendarHTML = '<table class="table table-bordered"><thead><tr>';

    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    daysOfWeek.forEach(day => {
      calendarHTML += `<th>${day}</th>`;
    });

    calendarHTML += '</tr></thead><tbody><tr>';

    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    for (let i = 0; i < firstDayOfMonth; i++) {
      calendarHTML += '<td></td>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
      if ((day + firstDayOfMonth - 1) % 7 === 0) {
        calendarHTML += '</tr><tr>';
      }
      if (day === currentDay) {
        calendarHTML += `<td class="bg-primary text-white">${day}</td>`;
      } else {
        calendarHTML += `<td>${day}</td>`;
      }
    }

    const lastDayOfMonth = new Date(currentYear, currentMonth, daysInMonth).getDay();
    for (let i = lastDayOfMonth + 1; i < 7; i++) {
      calendarHTML += '<td></td>';
    }

    calendarHTML += '</tr></tbody></table>';
    calendarElement.innerHTML = calendarHTML;
  });
</script>

<?php
include("./includes/footer.php");
?>

<style>

  
/* Dashboard Info Cards */
.info-card {
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
  margin-bottom: 20px;
}

.info-card .card-icon {
  font-size: 32px;
  line-height: 0;
  width: 64px;
  height: 64px;
  flex-shrink: 0;
  flex-grow: 0;
  background: #f6f6fe;
  color:rgb(233, 75, 75);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-card h5 {
  font-size: 18px;
  font-weight: 600;
  color: #012970;
}

.info-card h6 {
  font-size: 24px;
  font-weight: 700;
  color: #012970;
}

.info-card .btn {
  margin-top: 10px;
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background: #52e42e;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
}

.info-card .btn:hover {
  background: #5bbf4a;
}

  </style>
<div>
  <!-- Main content -->
  <div>
    <!-- Content header -->
    <x-dashboard.header heading="User Dashboard" />

    <!-- Content -->
    <div class="mt-2">

      <!-- State cards -->
      <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.state-card title="Login Users" count="{{ number_format($login_user_count) }}" wire:poll.60s='fetch_login_users()'>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </x-dashboard.state-card>

        <a wire:navigate href="{{ route('user.all_user') }}">
          <x-dashboard.state-card title="Users" count="{{ number_format($user_count) }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </x-dashboard.state-card>
        </a>

        <a wire:navigate href="{{ route('user.all_faculty') }}">
          <x-dashboard.state-card title="Faculties" count="{{ number_format($faculty_count) }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </x-dashboard.state-card>
        </a>

        <x-dashboard.state-card title="Students" count="{{ number_format($student_count) }}">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
        </x-dashboard.state-card>

      </div>
      <!-- State cards -->
      <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4">
        <a wire:navigate href="{{ route('user.all_pattern') }}">
          <x-dashboard.state-card title="Patterns" count="{{ number_format($pattern_count) }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </x-dashboard.state-card>
        </a>

        <a wire:navigate href="{{ route('user.all_programme') }}">
          <x-dashboard.state-card title="Programmes" count="{{ number_format($programe_count) }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
          </x-dashboard.state-card>
        </a>

        <a wire:navigate href="{{ route('user.all_course') }}">
          <x-dashboard.state-card title="Courses" count="{{ number_format($course_count) }}">
            <path stroke-linecap="round" stroke-width="2" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
          </x-dashboard.state-card>
        </a>
        
        {{-- <a wire:navigate href="{{ route('faculty.all_subjects') }}"> --}}
        <x-dashboard.state-card title="Subjects" count="{{ number_format($subject_count) }}">
          <path fill-rule="round" stroke-width="2" stroke-linejoin="round" d="M6 2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 1 0 0-2h-2v-2h2c.6 0 1-.4 1-1V4a2 2 0 0 0-2-2h-8v16h5v2H7a1 1 0 1 1 0-2h1V2H6Z" />
          </path>
        </x-dashboard.state-card>
        {{-- </a> --}}

      </div>
      <div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-2 xl:grid-cols-4">
        <a wire:navigate href="{{ route('user.all_exam') }}">
          <x-dashboard.state-card title="Exams" count="{{ number_format($exam_count) }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
          </x-dashboard.state-card>
        </a>

        {{--
          <x-dashboard.state-card title="Tickets" count="20,516" groth="+3.1%">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
          </x-dashboard.state-card> --}}
      </div>

      <!-- Charts -->
      {{-- <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">
        <div class="col-span-2">
          <!-- Bar chart card -->
          <x-dashboard.bar-chart-card name="Bar Chart" toggle="Last Year">
            <canvas id="barChart"></canvas>
          </x-dashboard.bar-chart-card>
        </div>
        <div>
          <!-- Doughnut chart card -->
          <x-dashboard.doughnut-chart-card name="Doughnut Chart" toggle="Include Seb">
            <canvas id="doughnutChart"></canvas>
          </x-dashboard.doughnut-chart-card>
        </div>

      </div>

      <!-- Two grid columns -->
      <div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-3">
        <div class="col-span-1">
          <!-- Active users chart -->
          <x-dashboard.active-user-chart name="Active users right now" lable="Users" id="usersCount" count="0">
            <canvas id="activeUsersChart"></canvas>
          </x-dashboard.active-user-chart>
        </div>
        <div class="col-span-2">
          <!-- Line chart card -->
          <x-dashboard.line-chart-card name="Line Chart" toggle=" ">
            <canvas id="lineChart"></canvas>
          </x-dashboard.line-chart-card>
        </div>
      </div>

    </div>
    @section('scripts')
      <script>
        var updateBarChart = (on) => {
          var data = {
            data: randomData(),
            backgroundColor: 'rgb(207, 250, 254)',
          }
          if (on) {
            barChart.data.datasets.push(data)
            barChart.update()
          } else {
            barChart.data.datasets.splice(1)
            barChart.update()
          }
        }

        var updateDoughnutChart = (on) => {
          var data = random()
          var color = 'rgb(207, 250, 254)'
          if (on) {
            doughnutChart.data.labels.unshift('Seb')
            doughnutChart.data.datasets[0].data.unshift(data)
            doughnutChart.data.datasets[0].backgroundColor.unshift(color)
            doughnutChart.update()
          } else {
            doughnutChart.data.labels.splice(0, 1)
            doughnutChart.data.datasets[0].data.splice(0, 1)
            doughnutChart.data.datasets[0].backgroundColor.splice(0, 1)
            doughnutChart.update()
          }
        }

        var updateLineChart = () => {
          lineChart.data.datasets[0].data.reverse()
          lineChart.update()
        }
        var random = (max = 100) => {
          return Math.round(Math.random() * max) + 20
        }

        var randomData = () => {
          return [
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
            random(),
          ]
        }

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

        var cssColors = (color) => {
          return getComputedStyle(document.documentElement).getPropertyValue(color)
        }

        var getColor = () => {
          return window.localStorage.getItem('color') ?? 'cyan'
        }

        var colors = {
          primary: cssColors(`--color-${getColor()}`),
          primaryLight: cssColors(`--color-${getColor()}-light`),
          primaryLighter: cssColors(`--color-${getColor()}-lighter`),
          primaryDark: cssColors(`--color-${getColor()}-dark`),
          primaryDarker: cssColors(`--color-${getColor()}-darker`),
        }

        var barChart = new Chart(document.getElementById('barChart'), {
          type: 'bar',
          data: {
            labels: months,
            datasets: [{
              data: randomData(),
              backgroundColor: colors.primary,
              hoverBackgroundColor: colors.primaryDark,
            }, ],
          },
          options: {
            scales: {
              yAxes: [{
                gridLines: false,
                ticks: {
                  beginAtZero: true,
                  stepSize: 50,
                  fontSize: 12,
                  fontColor: '#97a4af',
                  fontFamily: 'Open Sans, sans-serif',
                  padding: 10,
                },
              }, ],
              xAxes: [{
                gridLines: false,
                ticks: {
                  fontSize: 12,
                  fontColor: '#97a4af',
                  fontFamily: 'Open Sans, sans-serif',
                  padding: 5,
                },
                categoryPercentage: 0.5,
                maxBarThickness: '10',
              }, ],
            },
            cornerRadius: 2,
            maintainAspectRatio: false,
            legend: {
              display: false,
            },
          },
        })

        var doughnutChart = new Chart(document.getElementById('doughnutChart'), {
          type: 'doughnut',
          data: {
            labels: ['Oct', 'Nov', 'Dec'],
            datasets: [{
              data: [random(), random(), random()],
              backgroundColor: [colors.primary, colors.primaryLighter, colors.primaryLight],
              hoverBackgroundColor: colors.primaryDark,
              borderWidth: 0,
              weight: 0.5,
            }, ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              position: 'bottom',
            },

            title: {
              display: false,
            },
            animation: {
              animateScale: true,
              animateRotate: true,
            },
          },
        })

        var activeUsersChart = new Chart(document.getElementById('activeUsersChart'), {
          type: 'bar',
          data: {
            labels: [...randomData(), ...randomData()],
            datasets: [{
              data: [...randomData(), ...randomData()],
              backgroundColor: colors.primary,
              borderWidth: 0,
              categoryPercentage: 1,
            }, ],
          },
          options: {
            scales: {
              yAxes: [{
                display: false,
                gridLines: false,
              }, ],
              xAxes: [{
                display: false,
                gridLines: false,
              }, ],
              ticks: {
                padding: 10,
              },
            },
            cornerRadius: 2,
            maintainAspectRatio: false,
            legend: {
              display: false,
            },
            tooltips: {
              prefix: 'Users',
              bodySpacing: 4,
              footerSpacing: 4,
              hasIndicator: true,
              mode: 'index',
              intersect: true,
            },
            hover: {
              mode: 'nearest',
              intersect: true,
            },
          },
        })

        var lineChart = new Chart(document.getElementById('lineChart'), {
          type: 'line',
          data: {
            labels: months,
            datasets: [{
              data: randomData(),
              fill: false,
              borderColor: colors.primary,
              borderWidth: 2,
              pointRadius: 0,
              pointHoverRadius: 0,
            }, ],
          },
          options: {
            responsive: true,
            scales: {
              yAxes: [{
                gridLines: false,
                ticks: {
                  beginAtZero: false,
                  stepSize: 50,
                  fontSize: 12,
                  fontColor: '#97a4af',
                  fontFamily: 'Open Sans, sans-serif',
                  padding: 20,
                },
              }, ],
              xAxes: [{
                gridLines: false,
              }, ],
            },
            maintainAspectRatio: false,
            legend: {
              display: false,
            },
            tooltips: {
              hasIndicator: true,
              intersect: false,
            },
          },
        })

        var randomUserCount = 0

        var usersCount = document.getElementById('usersCount')

        var fakeUsersCount = () => {
          randomUserCount = random()
          activeUsersChart.data.datasets[0].data.push(randomUserCount)
          activeUsersChart.data.datasets[0].data.splice(0, 1)
          activeUsersChart.update()
          usersCount.innerText = randomUserCount
        }

        setInterval(() => {
          fakeUsersCount()
        }, 1000)
      </script>
    @endsection --}}
    </div>
  </div>

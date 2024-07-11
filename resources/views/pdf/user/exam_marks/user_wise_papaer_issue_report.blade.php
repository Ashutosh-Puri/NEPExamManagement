<style>
    table,
    td,
    th {
      border-collapse: collapse;
    }
  </style>
<div>
    <h1 style="text-align: center;">User Wise Paper Issue Report</h1>
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align: center;"> Exam : {{ $exam->exam_name }}</th>
            </tr>
            <tr>
                <th style="text-align: left;">No</th>
                <th style="text-align: left;">User Name</th>
                <th style="text-align: left;">Total Papers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userWiseReport as $report)
                <tr>
                    <td>{{ $report['id'] }}</td>
                    <td>{{ $report['user_name'] }}</td>
                    <td>{{ $report['total_papers'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

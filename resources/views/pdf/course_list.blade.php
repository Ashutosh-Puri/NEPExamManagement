<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <style>
      
        /* Waternark */
        section {
            opacity: 0.2;
            background-image: url('{{ public_path('img/logo-20.png') }}');
            background-size: 200px 200px;
            background-repeat: repeat;
            background-color: rgba(255, 255, 255, 0.5);
        }

        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
    </style>
</head>
<body>
    <section>
        <h1>Course List</h1>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Full Name</th>
                    <th>Special Subject</th>
                    <th>Course Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{ $course->course_name }}</td>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->fullname }}</td>
                    <td>{{ $course->special_subject }}</td>
                    <td>{{ $course->course_type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</body>
</html>

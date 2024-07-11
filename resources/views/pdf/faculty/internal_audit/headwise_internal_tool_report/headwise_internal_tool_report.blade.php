<style>
    table,
    td,
    th {
        border-collapse: collapse;
        border: 1px solid black;
        padding: 5px;
    }
</style>

<table style="width: 100%;">
    <tr>
        <th colspan="10">
            Sangamner Nagarpalika Arts, D.J. Malpani Commerce and B.N. Sarda Science College (Autonomous) Sangamner <br> (Affiliated to Savitribai Phule Pune University)
        </th>
    </tr>
    <tr>
        <th colspan="10">
            Department Head : {{ $department_head }}
        </th>
    </tr>
    <tr>
        <th colspan="10">
            Subjects
        </th>
    </tr>
    <tr>
        <th colspan="2">Sr.No.</th>
        <th colspan="8" align="left">Subject Name</th>
    </tr>
    @php
        $counter = 1;
    @endphp
    @foreach ($subject_names as $subject_name)
    <tr>
        <td colspan="2" align="center">{{ $counter++ }}</td>
        <td colspan="8">{{ $subject_name }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="5" style="font-weight:bold;">TOTAL SUBJECTS -</td>
        <td colspan="5"> {{ $total_subjects }}</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight:bold;">UPLOADED DOCUMENTS -</td>
        <td colspan="5"> {{ $total_uploaded_documents }}</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight:bold;">NOT UPLOADED DOCUMENTS -</td>
        <td colspan="5">{{ $not_uploaded_documents }}</td>
    </tr>
</table>

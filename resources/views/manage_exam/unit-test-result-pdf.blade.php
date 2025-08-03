<!DOCTYPE html>
<html>

<head>
    <title>Unit Test Result</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        .header {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2 align="center">{{ $selectedExam->exam_name }} (Class: {{ $selectedExam->class }} {{ $selectedExam->section ?
        '- ' . $selectedExam->section : '' }})</h2>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Class</th>
                {{ $selectedExam->section !== null ? '<th>'.$selectedExam->section.'</th>' : '' }}
                <th>Roll No</th>
                <th>Full Marks</th>
                <th>Pass Marks</th>
                <th>Marks obtained</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($studentResults as $i => $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['class'] }}</td>
                {{ $student['section'] !== null ? '<td>'.$student['section'].'</td>' : '' }}
                <td>{{ $student['roll_no'] }}</td>
                <td>{{ $student['total_marks'] }}</td>
                <td>{{ $student['total_pass_marks'] }}</td>
                <td>{{ $student['total_marks_obtained'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

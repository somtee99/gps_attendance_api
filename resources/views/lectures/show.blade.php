<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Lectures</h2>
<a href="{{ url('lecture/create') }}">
    <button>Create Lecture</button> 
</a>
<table>
  <tr>
    <th>Course Code</th>
    <th>Course Title</th>
    <th>Hall</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Action</th>
  </tr>
@php
  
    $lectures = App\Models\Lecture::all();
  
@endphp

@foreach ($lectures as $lecture)
    @php
    
    $course = App\Models\Course::where('uuid', $lecture->course_uuid)->first();
    $hall = App\Models\Hall::where('uuid', $lecture->hall_uuid)->first();

    @endphp
    <tr>
        <td>{{ $course->course_code }}</td>
        <td>{{ $course->title }}</td>
        <td>{{ $hall->name }}</td>
        <td>{{ $lecture->start_time }}</td>
        <td>{{ $lecture->end_time }}</td>
        <td>
            <a href="{{ url('/attendance/'.$lecture->uuid) }}">
                <button>View Attendance</button> 
            </a>
        </td>
    </tr>
@endforeach
  
  
</table>

</body>
</html>

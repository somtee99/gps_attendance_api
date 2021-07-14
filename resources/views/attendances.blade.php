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

<h2>Lecture Attendance</h2>
</br>
<table>
  <tr>
    <th>Last Name</th>
    <th>First Name</th>
    <th>Matric No</th>
    <th>Email</th>
  </tr>

@foreach ($attendees as $attendee)
    <tr>
        <td>{{ $attendee->last_name }}</td>
        <td>{{ $attendee->first_name }}</td>
        <td>{{ $attendee->matric_no }}</td>
        <td>{{ $attendee->email }}</td>
    </tr>
@endforeach
  
  
</table>

</body>
</html>

<!DOCTYPE html>   
<html>   
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> Login Page </title>  
<style>   
Body {  
  font-family: Calibri, Helvetica, sans-serif;  
  /* background-color: pink;   */
}  
button {   
       background-color: #4CAF50;   
       width: 100%;  
        color: orange;   
        padding: 15px;   
        margin: 10px 0px;   
        border: none;   
        cursor: pointer;   
         }   
 form {   
        border: 3px solid #f1f1f1;   
    }   
 input[type=text], input[type=password] {   
        width: 100%;   
        margin: 8px 0;  
        padding: 12px 20px;   
        display: inline-block;   
        border: 2px solid green;   
        box-sizing: border-box;   
    }  
 button:hover {   
        opacity: 0.7;   
    }   
  .cancelbtn {   
        width: auto;   
        padding: 10px 18px;  
        margin: 10px 5px;  
    }   
        
     
 .container {   
        padding: 25px;   
        background-color: lightblue;  
    }   
</style>   
</head>    
@php
    $halls = App\Models\Hall::all();
    $courses = App\Models\Course::all();

@endphp
<body>    
    <h3> Create Lecture </h3>  
    <form action="{{ url('create-lecture-action') }}" method="POST">  
        @csrf
        @method('POST')
        <div class="container">  
        <label for="hall_uuid">Choose a Hall</label></br>
        
        <select name="hall_uuid" id="hall_uuid">
        @foreach ($halls as $hall)
            <option value="{{ $hall->uuid }}">{{ $hall->name }}</option>
        @endforeach
        </select> 
        </br>

        <label for="course_uuid">Select a Course</label></br>
        
        <select name="course_uuid" id="course_uuid">
        @foreach ($courses as $course)
            <option value="{{ $course->uuid }}">{{ $course->course_code }}</option>
        @endforeach
        </select> 
        </br>

        <label for="start_time">Start Time (date and time):</label></br>
        <input type="datetime-local" id="start_time" name="start_time" required>
        </br>

        <label for="end_time">End Time (date and time):</label></br>
        <input type="datetime-local" id="end_time" name="end_time" required>

            
        <button type="submit">Create</button>   
             
        </div>   
    </form>     
</body>     
</html> 
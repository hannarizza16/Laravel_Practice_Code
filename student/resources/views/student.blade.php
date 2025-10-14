<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    @if ($success = Session::get('success'))
        <div class="alert alert-success alert-block">
            <!-- <button type="button" class="close" data-dismiss="alert">×</button>	 -->
            <strong>{{ $success }}</strong>
        </div>
    @endif



    @if ($errors->any()) 
        <div class="alert alert-danger alert-block">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="container">
        <h2 class="mb-4">Student Records</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Subject</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->student }}</td>
                    <td>{{ $student->grade }}</td>
                    <td>{{ $student->subject }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        <form action="{{ route("student.destroy", $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                onclick='return confirm("Are you sure you want to delete {{$student->student}}")'> 
                                Delete 
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="container">
        <h2> Add new student grade</h2>

        <form action="{{ route('student.create.store') }}" method="POST">
            @csrf 
            <input type="text" name="student" placeholder="Name">
            <input type="text" name="grade" placeholder="Grade">
            <input type="text" name="subject" placeholder="Subject">
            <input type="email" name="email" placeholder="Student Email">

            <button type="submit">Add</button>
        </form>
    </div>

    <div>
        @foreach ($students as $student ) 
            <br>{{ $student->student }}
        @endforeach

    </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery -->
    <script>
        $(document).ready(function() {
            // fade out after 3 seconds
            setTimeout(function() {
                $(".alert-success, .alert-danger").fadeOut('slow');
            }, 5000); // 3000ms = 3 seconds
        });
    </script>

<!-- {"grade":["The grade field is required."],"subject":["The subject field is required."]} -->
</body>
</html>

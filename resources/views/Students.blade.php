<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>LARAVEL + AJAX</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  </head>
  <body>
    <section style="padding-top:60px;">
        <div class="container">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                        Students Data
                            </div>
                            <div class="col-4">
                                <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#StudentModal">Add New Student</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="studentTable" class="table">
                            <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $s)
                                <tr id="sid{{$s->id}}">
                                    <td>{{ $s->firstname }}</td>
                                    <td>{{ $s->lastname }}</td>
                                    <td>{{ $s->email }}</td>
                                    <td>{{ $s->phone }}</td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="editStudent({{ $s->id }})" class="btn btn-warning">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Edit Modal -->
<div class="modal fade" id="StudentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Student Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="studentEditForm">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" name="firstname" id="firstname2" class="form-control">
                <label for="lastname">Lastname</label>
                <input type="text" name="lastname" id="lastname2" class="form-control">
                <label for="email">Email</label>
                <input type="text" name="email" id="email2" class="form-control">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone2" class="form-control">
            </div>
            <hr>
            <button type="submit" class="btn btn-block btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="StudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="studentForm">
            @csrf
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" name="firstname" id="firstname" class="form-control">
                <label for="lastname">Lastname</label>
                <input type="text" name="lastname" id="lastname" class="form-control">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>
            <hr>
            <button type="submit" class="btn btn-block btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    function editStudent(id){
        $.get('/students/'+id, function(student) {
            $("#id").val(student.id);
            $("#firstname2").val(student.firstname);
            $("#lastname2").val(student.lastname);
            $("#email2").val(student.email);
            $("#phone2").val(student.phone);
            $("#StudentEditModal").modal('toggle');
        })
    }

    $("#studentEditForm").submit(function(e){
        e.preventDefault();
        let id = $("#id").val();
        let firstname = $("#firstname2").val();
        let lastname = $("#lastname2").val();
        let email = $("#email2").val();
        let phone = $("#phone2").val();
        let _token = $("input[name=_token]").val();

        $.ajax({
            url: "{{ route('student.update') }}",
            type: "put",
            data: {
                id:id,
                firstname:firstname,
                lastname:lastname,
                email:email,
                phone:phone,
                _token:_token
            },
            success: function(response){
                $('#sid'+response.id+'td:nth-child(1)').text(response.firstname);
                $('#sid'+response.id+'td:nth-child(2)').text(response.lastname);
                $('#sid'+response.id+'td:nth-child(3)').text(response.email);
                $('#sid'+response.id+'td:nth-child(4)').text(response.phone);
                $("#studentEditModal").modal("toggle");
                $("#studentEditForm")[0].reset();
                $("#StudentEditModal").modal("hide");
            }
        });
    });
</script>

<script>
$("#studentForm").submit(function(e){
    e.preventDefault();

    let firstname = $("#firstname").val();
    let lastname = $("#lastname").val();
    let email = $("#email").val();
    let phone = $("#phone").val();
    let _token = $("input[name=_token]").val();

    $.ajax({
        url: "{{ route('student.add') }}",
        type: "POST",
        data: {
            firstname:firstname,
            lastname:lastname,
            email:email,
            phone:phone,
            _token:_token
        },
        success: function(response){
            if (response) {
                $("#studentTable").prepend('<tr><td>'+ response.firstname +'</td><td>'+ response.lastname +'</td><td>'+ response.email +'</td><td>'+ response.phone +'</td><td><a href="javascript:void(0)" onclick="editStudent({{ $s->id }})" class="btn btn-warning">Edit</a></td></tr>');
                $("#studentForm")[0].reset();
                $("#StudentModal").modal("hide");
            }
        }
    });
});
</script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    -->
  </body>
</html>
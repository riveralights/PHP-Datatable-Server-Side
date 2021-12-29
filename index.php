<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Server Side Data Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <h1>Server Side Movies List</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table id="movie_table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Movie Name</th>
                            <th>Year Release</th>
                            <th>Genre</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                </table>
                <div class="my-3 d-flex justify-content-end">
                    <button type="button" id="add_button" class="btn btn-primary" data-toggle="modal" data-target="#movieModal">
                        Add Movies
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="movie_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Movie</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" id="year" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Genre</label>
                            <input type="text" name="genre" id="genre" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="movie_id" id="movie_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" value="Add" name="action" id="action" class="btn btn-primary">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function(){
            $('#add_button').click(function(){ // tombol add_button, ketika di click, jalani fungsi berikut ini
                $('#movie_form')[0].reset(); 
                $('.modal-title').text("Add Movie Details");
                $('#action').val("Add New Movie");
                $('#operation').val("Add");
            });

            var dataTable = $('#movie_table').DataTable({
                "paging": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "info": true,
                "ajax": {
                    url: "fetch.php",
                    type: "POST"
                },
                "columnDefs": [
                    {
                        "targets": [0, 3, 4],
                        "orderable": false,
                    },
                ]
            });

            $(document).on('submit', '#movie_form', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                var title = $('#title').val();
                var year = $('#year').val();
                var genre = $('#genre').val();

                if( title != '' && year != '' && genre != '' ) {
                    $.ajax({
                        url: "insert.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $('#movie_form')[0].reset();
                            $('#movieModal').modal('hide');
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    alert('Title, Year and Genre are Required');
                }
            });

            $(document).on('click', '.update', function() {
                var movie_id = $(this).attr("id");
                $.ajax({
                    url: 'fetch_single.php',
                    method: 'POST',
                    data: {
                        movie_id: movie_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#movieModal').modal('show');
                        $('#id').val(data.id);
                        $('#title').val(data.title);
                        $('#year').val(data.year);
                        $('#genre').val(data.genre);
                        $('.modal-title').text("Edit Movie Details");
                        $('#movie_id').val(movie_id);
                        $('#action').val("Update");
                        $('#operation').val("Edit");
                    }
                });
            });

            $(document).on('click', '.delete', function(){
                var movie_id = $(this).attr("id");
                if(confirm("Are you sure you want to delete this user?")){
                    $.ajax({
                        url:"delete.php",
                        method:"POST",
                        data:{movie_id:movie_id},
                        success:function(data)
                        {
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    return false;   
                }
            });
        });
    </script>
</body>
</html>
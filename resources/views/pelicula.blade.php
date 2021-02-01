@extends('welcome')

@section('content')
    <div class="container pt-4">
        <h2>Listado de Peliculas</h2>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Titulo</th>
                    <th>Director</th>
                    <th width="300px">Accion</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="peliculaForm" name="peliculaForm" class="form-horizontal">
                    <input type="hidden" name="pelicula_id" id="pelicula_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Titulo</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Enter titulo" value="" maxlength="50" required="">
                            </div>
                        </div>
        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Director</label>
                            <div class="col-sm-12">
                                <textarea id="director" name="director" required="" placeholder="Enter director" class="form-control"></textarea>
                            </div>
                        </div>
        
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Guardar cambios
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  
    <script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('peliculas.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'titulo', name: 'titulo'},
                {data: 'director', name: 'director'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#createNewPelicula').click(function () {
            $('#saveBtn').val("create-pelicula");
            $('#pelicula_id').val('');
            $('#peliculaForm').trigger("reset");
            $('#modelHeading').html("Crear nueva pelicula");
            $('#ajaxModel').modal('show');
        });
        $('body').on('click', '.editPelicula', function () {
        var pelicula_id = $(this).data('id');
        $.get("{{ route('peliculas.index') }}" +'/' + pelicula_id +'/edit', function (data) {
            $('#modelHeading').html("Editar Pelicula");
            $('#saveBtn').val("edit-pelicula");
            $('#ajaxModel').modal('show');
            $('#pelicula_id').val(data.id);
            $('#titulo').val(data.titulo);
            $('#director').val(data.director);
        })
    });
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Guardar');
        
            $.ajax({
            data: $('#peliculaForm').serialize(),
            url: "{{ route('peliculas.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
        
                $('#peliculaForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();
            
            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Guardar cambios');
            }
        });
        });
        
        $('body').on('click', '.deletePelicula', function () {
        
            var pelicula_id = $(this).data("id");
            confirm("Are You sure want to delete !");
        
            $.ajax({
                type: "DELETE",
                url: "{{ route('peliculas.store') }}"+'/'+pelicula_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
        
    });
    </script>
@endsection
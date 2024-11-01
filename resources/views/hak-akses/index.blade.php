@extends('layouts.app')

@include('hak-akses.create')
@include('hak-akses.edit')


@section('content')
<section class="section">
    <div class="section-header">
      <h1>Role / Hak Akses</h1>
      <div class="ml-auto">
        <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_role"><i class="fa fa-plus"></i> Tambah Role</a>
       </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Role</th>
                                        <th>Deskripsi</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>

<!-- Datatable Jquery -->
<script>
    $(document).ready(function(){
        $('#table_id').DataTable();
    })
</script>

<!-- Fetch Data -->
<script>
    $.ajax({
        url: "/hak-akses/get-data",
        type: "GET",
        dataType: 'JSON',
        success: function(response){
            let counter = 1;
            $('#table_id').DataTable().clear();
            $.each(response.data, function(key, value){
                let role = `
                    <tr class="role-row" id="index_${value.id}">
                        <td>${counter++}</td>
                        <td>${value.role}</td>
                        <td>${value.deskripsi}</td>
                        <td>
                            <a href="javascript:void(0)" id="button_edit_role" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                            <a href="javascript:void(0)" id="button_hapus_role" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                        </td>
                    </tr>
                `;
                $('#table_id').DataTable().row.add($(role)).draw(false);
            });
        }
    });
</script>

<!-- Show Modal Create -->
<script>
    $('body').on('click', '#button_tambah_role', function(){
        $('#modal_tambah_role').modal('show');
        resetAlerts();
    });

    function resetAlerts() {
        $('#alert-role').removeClass('d-block').addClass('d-none');
        $('#alert-deskripsi').removeClass('d-block').addClass('d-none');
    }

    $('#store').click(function(e){
        e.preventDefault();

        let role            = $('#role').val();
        let deskripsi       = $('#deskripsi').val();
        let token           = $("meta[name='csrf-token']").attr("content");

        let formData = new FormData();
        formData.append('role', role);
        formData.append('deskripsi', deskripsi);
        formData.append('_token', token);

        $.ajax({
            url: '/hak-akses',
            type: "POST",
            cache: false,
            data: formData,
            contentType: false,
            processData: false,

            success:function(response){
                swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: `${response.message}`,
                    showConfirmButton: true,
                    timer: 3000
                });

                $.ajax({
                    url : '/hak-akses/get-data',
                    type: "GET",
                    dataType: 'JSON',
                    cache: false,
                    success:function(response){
                        $('#table-role').html(''); // kosongkan tabel terlebih dahulu
                        
                        let counter = 1;
                        $('#table_id').DataTable().clear();
                        $.each(response.data, function(key, value) {
                            let role = `
                            <tr class="role-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.role}</td>
                                <td>${value.deskripsi}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_edit_role" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_role" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                            `;
                            $('#table_id').DataTable().row.add($(role)).draw(false);
                        });

                        $('#role').val('');
                        $('#deskripsi').val('');

                        $('#modal_tambah_role').modal('hide');

                        let table = $('#table_id').DataTable();
                        table.draw(); // memperbarui Datatables
                    },
                });
            },

            error:function(error){
                if(error.responseJSON && error.responseJSON.role && error.responseJSON.role[0]){
                    $('#alert-role').removeClass('d-none');
                    $('#alert-role').addClass('d-block');

                    $('#alert-role').html(error.responseJSON.role[0]);
                }

                if(error.responseJSON && error.responseJSON.deskripsi && error.responseJSON.deskripsi[0]){
                    $('#alert-deskripsi').removeClass('d-none');
                    $('#alert-deskripsi').addClass('d-block');

                    $('#alert-deskripsi').html(error.responseJSON.deskripsi[0]);
                }
            }
        })
    });
</script>


<!-- Edit/Update Data -->
<script>
    $('body').on('click', '#button_edit_role', function(){
        let role_id = $(this).data('id');

        $.ajax({
            url: `/hak-akses/${role_id}/edit`,
            type: "GET",
            cache: false,
            success: function(response){
                $('#role_id').val(response.data.id);
                $('#edit_role').val(response.data.role);
                $('#edit_deskripsi').val(response.data.deskripsi);

                $('#modal_edit_role').modal('show');
            }
        });
    });

    $('#update').click(function(e){
        e.preventDefault();

        let role_id         = $('#role_id').val();
        let role            = $('#edit_role').val();
        let deskripsi       = $('#edit_deskripsi').val();
        let token           = $("meta[name='csrf-token']").attr("content");

        let formData = new FormData();
        formData.append('role', role);
        formData.append('deskripsi', deskripsi);
        formData.append('_token', token);
        formData.append('_method', 'PUT');

        $.ajax({
            url: `/hak-akses/${role_id}`,
            type: "POST",
            cache: false,
            data: formData,
            contentType: false,
            processData: false,

            success:function(response){
                swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: `${response.message}`,
                    showConfirmButton: true,
                    timer:3000
                });

                $.ajax({
                    url : '/hak-akses/get-data',
                    type: "GET",
                    dataType: 'JSON',
                    cache: false,
                    success:function(response){
                        $('#table-role').html(''); // kosongkan tabel terlebih dahulu
                        
                        let counter = 1;
                        $('#table_id').DataTable().clear();
                        $.each(response.data, function(key, value) {
                            let role = `
                            <tr class="role-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.role}</td>
                                <td>${value.deskripsi}</td>
                                <td>
                                    <a href="javascript:void(0)" id="button_edit_role" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                    <a href="javascript:void(0)" id="button_hapus_role" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                            `;
                            $('#table_id').DataTable().row.add($(role)).draw(false);
                        });
                        $('#role').val('');
                        $('#deskripsi').val('');
                            
                        $('#modal_edit_role').modal('hide');
                    }
                });
            },

            error:function(error){
                if(error.responseJSON && error.responseJSON.role && error.responseJSON.role[0]){
                    $('#alert-role').removeClass('d-none');
                    $('#alert-role').addClass('d-block');

                    $('#alert-role').html(error.responseJSON.role[0]);
                }

                if(error.responseJSON && error.responseJSON.deskripsi && error.responseJSON.deskripsi[0]){
                    $('#alert-deskripsi').removeClass('d-none');
                    $('#alert-deskripsi').addClass('d-block');

                    $('#alert-deskripsi').html(error.responseJSON.deskripsi[0]);
                }
            }
        });
    });
</script>

<!-- Delete Data -->
<script>
    $('body').on('click', '#button_hapus_role', function(){
        let role_id     = $(this).data('id');
        let token       = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Apakah Anda Yakin ?',
            text: "ingin menghapus data ini !",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: `/hak-akses/${role_id}`,
                    type: "DELETE",
                    cache: false,
                    data: {
                        "_token": token
                    },
                    success:function(response){
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: true,
                            timer: 3000
                        });
                        $(`#index_${role_id}`).remove();
                    }
                })
            }
        })
    })
</script>


@endsection


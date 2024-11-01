@extends('layouts.app')

@include('cabang.create')
@include('cabang.edit')


@section('content')
<section class="section">
    <div class="section-header">
      <h1>Data Cabang</h1>
      <div class="ml-auto">
        <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_cabang"><i class="fa fa-plus"></i> Tambah Cabang</a>
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
                                        <th>Cabang</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
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
        url: "/cabang/get-data",
        type: "GET",
        dataType: 'JSON',
        success: function(response){
            let counter = 1;
            $('#table_id').DataTable().clear();
            $.each(response.data, function(key, value){
                let cabang = `
                    <tr class="cabang-row" id="index_${value.id}">
                        <td>${counter++}</td>
                        <td>${value.cabang}</td>
                        <td>${value.alamat}</td>
                        <td>
                            <a href="javascript:void(0)" id="button_edit_cabang" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                            <a href="javascript:void(0)" id="button_hapus_cabang" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                        </td>
                    </tr>
                `;
                $('#table_id').DataTable().row.add($(cabang)).draw(false);
            });
        }
    });
</script>

<!-- Show Modal Create -->
<script>
    $('body').on('click', '#button_tambah_cabang', function(){
        $('#modal_tambah_cabang').modal('show');
        resetAlerts();
    });

    function resetAlerts() {
        $('#alert-cabang').removeClass('d-block').addClass('d-none');
        $('#alert-alamat').removeClass('d-block').addClass('d-none');
    }

    $('#store').click(function(e){
        e.preventDefault();

        let cabang          = $('#cabang').val();
        let alamat          = $('#alamat').val();
        let token           = $("meta[name='csrf-token']").attr("content");

        let formData = new FormData();
        formData.append('cabang', cabang);
        formData.append('alamat', alamat);
        formData.append('_token', token);

        $.ajax({
            url: '/cabang',
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
                    url: "/cabang/get-data",
                    type: "GET",
                    dataType: 'JSON',
                    success: function(response){
                        let counter = 1;
                        $('#table_id').DataTable().clear();
                        $.each(response.data, function(key, value){
                            let cabang = `
                                <tr class="cabang-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.cabang}</td>
                                    <td>${value.alamat}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_edit_cabang" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_cabang" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                            $('#table_id').DataTable().row.add($(cabang)).draw(false);
                        });

                        $('#cabang').val('');
                        $('#alamat').val('');

                        $('#modal_tambah_cabang').modal('hide');

                        let table = $('#table_id').DataTable();
                        table.draw();
                    }
                });
            },

            error:function(error){
                if(error.responseJSON && error.responseJSON.cabang && error.responseJSON.cabang[0]){
                    $('#alert-cabang').removeClass('d-none');
                    $('#alert-cabang').addClass('d-block');

                    $('#alert-cabang').html(error.responseJSON.cabang[0]);
                }

                if(error.responseJSON && error.responseJSON.alamat && error.responseJSON.alamat[0]){
                    $('#alert-alamat').removeClass('d-none');
                    $('#alert-alamat').addClass('d-block');

                    $('#alert-alamat').html(error.responseJSON.alamat[0]);
                }
            }
        })
    });
</script>


<!-- Edit/Update Data -->
<script>
    $('body').on('click', '#button_edit_cabang', function(){
        let cabang_id = $(this).data('id');

        $.ajax({
            url: `/cabang/${cabang_id}/edit`,
            type: "GET",
            cache: false,
            success: function(response){
                $('#cabang_id').val(response.data.id);
                $('#edit_cabang').val(response.data.cabang);
                $('#edit_alamat').val(response.data.alamat);

                $('#modal_edit_cabang').modal('show');
            }
        });
    });

    $('#update').click(function(e){
        e.preventDefault();

        let cabang_id       = $('#cabang_id').val();
        let cabang          = $('#edit_cabang').val();
        let alamat          = $('#edit_alamat').val();
        let token           = $("meta[name='csrf-token']").attr("content");

        let formData = new FormData();
        formData.append('cabang', cabang);
        formData.append('alamat', alamat);
        formData.append('_token', token);
        formData.append('_method', 'PUT');

        $.ajax({
            url: `/cabang/${cabang_id}`,
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
                    url: "/cabang/get-data",
                    type: "GET",
                    dataType: 'JSON',
                    success: function(response){
                        let counter = 1;
                        $('#table_id').DataTable().clear();
                        $.each(response.data, function(key, value){
                            let cabang = `
                                <tr class="cabang-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.cabang}</td>
                                    <td>${value.alamat}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_edit_cabang" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_cabang" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                            $('#table_id').DataTable().row.add($(cabang)).draw(false);
                            $('#modal_edit_cabang').modal('hide');
                        });
                    }
                });
            },

            error:function(error){
                if(error.responseJSON && error.responseJSON.cabang && error.responseJSON.cabang[0]){
                    $('#alert-cabang').removeClass('d-none');
                    $('#alert-cabang').addClass('d-block');

                    $('#alert-cabang').html(error.responseJSON.cabang[0]);
                }

                if(error.responseJSON && error.responseJSON.alamat && error.responseJSON.alamat[0]){
                    $('#alert-alamat').removeClass('d-none');
                    $('#alert-alamat').addClass('d-block');

                    $('#alert-alamat').html(error.responseJSON.alamat[0]);
                }
            }
        });
    });
</script>

<!-- Delete Data -->
<script>
    $('body').on('click', '#button_hapus_cabang', function(){
        let cabang_id  = $(this).data('id');
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
                    url: `/cabang/${cabang_id}`,
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
                        $(`#index_${cabang_id}`).remove();
                    }
                })
            }
        })
    })
</script>


@endsection


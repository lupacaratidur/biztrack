@extends('layouts.app')

@include('minuman.create')
@include('minuman.edit')
@include('minuman.show')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Minuman</h1>
            <div class="ml-auto">
                <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_minuman"><i class="fa fa-plus"></i>
                    Tambah Minuman</a>
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
                                            <th>Gambar</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Cabang</th>
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


    <!-- Fetch Data -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });

        $.ajax({
            url: "/minuman/get-data",
            type: "GET",
            dataType: 'JSON',
            success: function(response) {
                let counter = 1;
                $('#table_id').DataTable().clear();
                $.each(response.data, function(key, value) {
                    let minuman = `
                    <tr class="minuman-row" id="index_${value.id}">
                        <td>${counter++}</td>
                        <td><img src="/storage/${value.gambar}" alt="gambar" style="width: 150px"; height:"150px"></td>
                        <td>${value.kode_minuman}</td>
                        <td>${value.nama_minuman}</td>
                        <td><span class="badge badge-secondary">RP. ${value.harga}</span></td>
                        <td>${value.cabang.cabang}</td>
                        <td>
                            <a href="javascript:void(0)" id="button_detail_minuman" data-id="${value.id}" class="btn btn-lg btn-success mb-2"><i class="far fa-eye"></i> </a>
                            <a href="javascript:void(0)" id="button_edit_minuman" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                            <a href="javascript:void(0)" id="button_hapus_minuman" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                        </td>
                    </tr>
                `;
                    $('#table_id').DataTable().row.add($(minuman)).draw(false);
                });
            }
        });
    </script>

    <!-- Show Modal Create -->
    <script>
        $('body').on('click', '#button_tambah_minuman', function() {
            $('#modal_tambah_minuman').modal('show');
            resetAlerts();
        });

        function resetAlerts() {
            $('#alert-gambar').removeClass('d-block').addClass('d-none');
            $('#alert-nama_minuman').removeClass('d-block').addClass('d-none');
            $('#alert-deskripsi').removeClass('d-block').addClass('d-none');
            $('#alert-harga').removeClass('d-block').addClass('d-none');
            $('#alert-cabang_id').removeClass('d-block').addClass('d-none');
        }

        $('#store').click(function(e) {
            e.preventDefault();

            let gambar = $('#gambar')[0].files[0];
            let nama_minuman = $('#nama_minuman').val();
            let deskripsi = $('#deskripsi').val();
            let harga = $('#harga').val();
            let cabang_id = $('#cabang_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('gambar', gambar);
            formData.append('nama_minuman', nama_minuman);
            formData.append('deskripsi', deskripsi);
            formData.append('harga', harga);
            formData.append('cabang_id', cabang_id);
            formData.append('_token', token);

            $.ajax({
                url: '/minuman',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: "/minuman/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let minuman = `
                                <tr class="minuman-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td><img src="/storage/${value.gambar}" alt="gambar" style="width: 150px"; height:"150px"></td>
                                    <td>${value.kode_minuman}</td>
                                    <td>${value.nama_minuman}</td>
                                    <td><span class="badge badge-secondary">RP. ${value.harga}</span></td>
                                    <td>${value.cabang.cabang}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_detail_minuman" data-id="${value.id}" class="btn btn-lg btn-success mb-2"><i class="far fa-eye"></i> </a>
                                        <a href="javascript:void(0)" id="button_edit_minuman" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_minuman" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                                $('#table_id').DataTable().row.add($(minuman)).draw(
                                    false);
                            });

                            $('#gambar').val('');
                            $('#preview').attr('src', '');
                            $('#nama_minuman').val('');
                            $('#deskripsi').val('');
                            $('#harga').val('');
                            $('#cabang_id').val('');

                            $('#modal_tambah_minuman').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        }
                    });
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.gambar && error.responseJSON.gambar[
                            0]) {
                        $('#alert-gambar').removeClass('d-none');
                        $('#alert-gambar').addClass('d-block');

                        $('#alert-gambar').html(error.responseJSON.gambar[0]);
                    }

                    if (error.responseJSON && error.responseJSON.nama_minuman && error.responseJSON
                        .nama_minuman[0]) {
                        $('#alert-nama_minuman').removeClass('d-none');
                        $('#alert-nama_minuman').addClass('d-block');

                        $('#alert-nama_minuman').html(error.responseJSON.nama_minuman[0]);
                    }

                    if (error.responseJSON && error.responseJSON.deskripsi && error.responseJSON
                        .deskripsi[0]) {
                        $('#alert-deskripsi').removeClass('d-none');
                        $('#alert-deskripsi').addClass('d-block');

                        $('#alert-deskripsi').html(error.responseJSON.deskripsi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.harga && error.responseJSON.harga[0]) {
                        $('#alert-harga').removeClass('d-none');
                        $('#alert-harga').addClass('d-block');

                        $('#alert-harga').html(error.responseJSON.harga[0]);
                    }

                    if (error.responseJSON && error.responseJSON.cabang_id && error.responseJSON
                        .cabang_id[0]) {
                        $('#alert-cabang_id').removeClass('d-none');
                        $('#alert-cabang_id').addClass('d-block');

                        $('#alert-cabang_id').html(error.responseJSON.cabang_id[0]);
                    }
                }
            })
        });
    </script>

    <!-- Show Modal Detail-->
    <script>
        $('body').on('click', '#button_detail_minuman', function() {
            let minuman_id = $(this).data('id');

            $.ajax({
                url: `/minuman/${minuman_id}/`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#minuman_id').val(response.data.id);
                    $('#detail_gambar').val(null);
                    $('#detail_nama_minuman').val(response.data.nama_minuman);
                    $('#detail_deskripsi').val(response.data.deskripsi);
                    $('#detail_harga').val(response.data.harga);
                    $('#detail_gambar_preview').attr('src', '/storage/' + response.data.gambar);
                    $('#detail_cabang_id').val(response.data.cabang_id);

                    $('#modal_detail_minuman').modal('show');
                }
            });
        });
    </script>

    <!-- Edit/Update Data -->
    <script>
        $('body').on('click', '#button_edit_minuman', function() {
            let minuman_id = $(this).data('id');

            $.ajax({
                url: `/minuman/${minuman_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#minuman_id').val(response.data.id);
                    $('#edit_gambar').val(null);
                    $('#edit_nama_minuman').val(response.data.nama_minuman);
                    $('#edit_deskripsi').val(response.data.deskripsi);
                    $('#edit_harga').val(response.data.harga);
                    $('#edit_gambar_preview').attr('src', '/storage/' + response.data.gambar);
                    $('#edit_cabang_id').val(response.data.cabang_id);

                    $('#modal_edit_minuman').modal('show');
                }
            });
        });

        $('#update').click(function(e) {
            e.preventDefault();

            let minuman_id = $('#minuman_id').val();
            let gambar = $('#edit_gambar')[0].files[0];
            let nama_minuman = $('#edit_nama_minuman').val();
            let deskripsi = $('#edit_deskripsi').val();
            let harga = $('#edit_harga').val();
            let cabang_id = $('#edit_cabang_id').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('gambar', gambar);
            formData.append('nama_minuman', nama_minuman);
            formData.append('deskripsi', deskripsi);
            formData.append('harga', harga);
            formData.append('cabang_id', cabang_id);
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/minuman/${minuman_id}`,
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: "/minuman/get-data",
                        type: "GET",
                        dataType: 'JSON',
                        success: function(response) {
                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let minuman = `
                                <tr class="minuman-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td><img src="/storage/${value.gambar}" alt="gambar" style="width: 150px"; height:"150px"></td>
                                    <td>${value.kode_minuman}</td>
                                    <td>${value.nama_minuman}</td>
                                    <td><span class="badge badge-secondary">RP. ${value.harga}</span></td>
                                    <td>${value.cabang.cabang}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_detail_minuman" data-id="${value.id}" class="btn btn-lg btn-success mb-2"><i class="far fa-eye"></i> </a>
                                        <a href="javascript:void(0)" id="button_edit_minuman" data-id="${value.id}" class="btn btn-lg btn-warning mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_minuman" data-id="${value.id}" class="btn btn-lg btn-danger mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                            `;
                                $('#table_id').DataTable().row.add($(minuman)).draw(
                                    false);
                                $('#modal_edit_minuman').modal('hide');
                            });
                        }
                    });
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.gambar && error.responseJSON.gambar[
                            0]) {
                        $('#alert-gambar').removeClass('d-none');
                        $('#alert-gambar').addClass('d-block');

                        $('#alert-gambar').html(error.responseJSON.gambar[0]);
                    }

                    if (error.responseJSON && error.responseJSON.nama_minuman && error.responseJSON
                        .nama_minuman[0]) {
                        $('#alert-nama_minuman').removeClass('d-none');
                        $('#alert-nama_minuman').addClass('d-block');

                        $('#alert-nama_minuman').html(error.responseJSON.nama_minuman[0]);
                    }

                    if (error.responseJSON && error.responseJSON.nama_deskripsi && error.responseJSON
                        .nama_deskripsi[0]) {
                        $('#alert-nama_deskripsi').removeClass('d-none');
                        $('#alert-nama_deskripsi').addClass('d-block');

                        $('#alert-nama_deskripsi').html(error.responseJSON.nama_deskripsi[0]);
                    }

                    if (error.responseJSON && error.responseJSON.harga && error.responseJSON.harga[0]) {
                        $('#alert-harga').removeClass('d-none');
                        $('#alert-harga').addClass('d-block');

                        $('#alert-harga').html(error.responseJSON.harga[0]);
                    }

                    if (error.responseJSON && error.responseJSON.cabang_id && error.responseJSON
                        .cabang_id[0]) {
                        $('#alert-cabang_id').removeClass('d-none');
                        $('#alert-cabang_id').addClass('d-block');

                        $('#alert-cabang_id').html(error.responseJSON.cabang_id[0]);
                    }
                }
            });
        });
    </script>

    <!-- Delete Data -->
    <script>
        $('body').on('click', '#button_hapus_minuman', function() {
            let minuman_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Anda Yakin ?',
                text: "ingin menghapus data ini !",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/minuman/${minuman_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $(`#index_${minuman_id}`).remove();
                        }
                    })
                }
            })
        })
    </script>

    <!-- Preview Image -->
    <script>
        function previewImage() {
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <script>
        function previewImageEdit() {
            edit_gambar_preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection

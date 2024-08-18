@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#password_confirmation').keyup(function() {
                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();
                if (password != confirmPassword) {
                    $('#passwordError').text('Password tidak cocok');
                } else {
                    $('#passwordError').text('');
                }
            });

            // Handle the visibility of the Prodi select based on the selected level
            $('#level').change(function() {
                var selectedLevel = $(this).val();
                if (selectedLevel === 'ap') {
                    $('#prodi').parent().show();
                } else if (selectedLevel === 'af') {
                    $('#prodi').parent().hide();
                }
            });

            if ($('#level').val() === 'af') {
                $('#prodi').parent().hide();
            }
        });
    </script>
@stop
@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tambah User Baru</h6>
                <div class="mb-4"></div>
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Ulangi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <div id="passwordError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="level">Level:</label>
                        <select class="form-control" id="level" name="level" required>
                            <option value="af">Admin Fakultas</option>
                            <option value="ap">Admin Prodi</option>
                        </select>
                    </div>

                    
                    <div class="form-group" >
                        <label for="level">Prodi</label>
                        <select name="prodi"  id="prodi" class="form-control">
                            <option value="">Pilih Prodi</option>
                            <option value="Sipil">Sipil</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Arsitektur">Arsitektur</option>
                            <option value="Perencanaan Wilayah dan Kota">Perencanaan Wilayah dan Kota</option>
                            </select>
                    </div>

                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

    </div>
    <!-- End of Page Content -->
@endsection

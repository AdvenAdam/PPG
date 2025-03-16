<style>
    @media screen and (min-width:640px) {
        #profile-wrap {
            width: 75% !important;
        }
    }

    #profile-wrap {
        width: 100%;
    }
</style>
@extends('layout.app')
@section('content')
    <div class="d-flex align-items-center h-100 justify-content-center p-3 p-md-5" style="">
        <div class="card card-stats rounded-4 mx-auto"id="profile-wrap">
            <div class="p-3 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="p-5 rounded-3" style="background-color: #F5F7FD">
                            <img src="{{ asset('assets/img/' . $user->foto) }}" alt="..." class="img-fluid" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="profile">
                            <div class="mt-5 mt-md-0">
                                <p>Nama : <br /> <span style="color:#6C757D"> {{ $user->nama }} </span></p>
                                <p>Email : <br /> <span style="color:#6C757D"> {{ $user->email }} </span></p>
                                <p>Jabatan : <br /> <span style="color:#6C757D"> {{ $user->jabatan }} </span></p>
                                <p>Alamat : <br />
                                    <span style="color:#6C757D"> {{ $user->kelompok ? $user->kelompok->nama . ',' : '' }}
                                    </span>
                                    <span style="color:#6C757D"> {{ $user->desa ? $user->desa->nama . ',' : '' }} </span>
                                    <span style="color:#6C757D"> {{ $user->daerah ? $user->daerah->nama : '' }} </span>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between mt-3 gap-3">
                                <button class="btn btn-primary gap-3" id="editProfile" onclick="editProfile()">
                                    <i class="fa fa-pen pe-2"></i> Edit Profile
                                </button>
                            </div>
                        </div>

                        <div id="form" hidden>
                            <form action="{{ url('/profile/edit/' . $user->id) }}" method="POST" id="addRowForm"
                                class="addRowForm row g-3" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="mt-2">
                                            <label for="nama">Nama User</label>
                                            <input type="text" class="form-control" name="nama"
                                                value="{{ $user->nama }}" placeholder="* isi Nama User" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mt-2">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="new_email" name="new_email"
                                                value="{{ $user->email }}" placeholder="* isi Email User" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mt-2">
                                            <label for="newpassword">Password Baru</label>
                                            <input type="newpassword" class="form-control" id="newpassword"
                                                name="newpassword" placeholder="isi Password Baru" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mt-2">
                                            <label for="repassword">Ketik Ulang Password</label>
                                            <input type="repassword" class="form-control" id="repassword" name="repassword"
                                                placeholder="Ketik Ulang Password" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mt-2">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="isi Password User" />
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-between mt-3 gap-3">
                                    <button type="button" id="addRowButton" class="btn btn-primary"
                                        onclick="this.disabled=true; this.form.submit();">
                                        Ubah Data
                                    </button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                                        onclick="closeForm()">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editProfile() {
            $('#profile').prop('hidden', true);
            $('#form').prop('hidden', false);
        }

        function closeForm() {
            $('#profile').prop('hidden', false);
            $('#form').prop('hidden', true);
        }
    </script>
@endsection

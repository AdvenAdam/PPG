<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - WebPPG</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="#" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />

    {{-- css custom --}}
    <link rel="stylesheet" href="assets/css/customs.css" />
</head>

<body class="d-flex justify-content-center align-items-center">
    <div class="container bg-white  border rounded-4 shadow" style="overflow: hidden; min-height: 50vh">
        <div class="row h-100">
            <div class="col-md-6 p-5" style="background-color: #59cc72">
                <div class=" h-100 mx-auto text-white w-100 lg-w-75">
                    <div class="p-3 p-lg-5">
                        <h1 class="text-center">WebPPG</h1>
                        <h5 class="text-center">Penggerak Pembina Generus (PPG) - Boyolali Barat</h5>
                        <img src="assets/img/logo.png" alt="logo" class="img-fluid d-block mx-auto my-3"
                            width="150px">
                        <div class="text-center"><span>Website ini berfungsi sebagai pusat informasi dan pendataan,
                                mendukung setiap upaya pembinaan generus LDII dalam
                                menciptakan generasi yang berkualitas.</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-5 mt-5">
                <div class="text-center fw-bold fs-1">Welcome Back</div>
                <div class="text-center text-black-50">Silahkan login terlebih dahulu</div>
                <form action="{{ route('login') }}" method="POST" class="mt-4">
                    @csrf
                    {{-- handle error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    <div class="mb-4">
                        <input type="email" class="form-control p-3 rounded-4 @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <input type="password"
                            class="form-control p-3 rounded-4 @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-4 p-3">Login</button>
                    </div>
                </form>

                {{-- footer --}}
                <div class="text-center mt-5">
                    <div class="text-center text-black-50">Copyright &copy;
                        <?php echo date('Y'); ?> - WebPPG
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
</body>

</html>

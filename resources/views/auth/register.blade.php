<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <meta name="author" content="Zoyothemes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/inventryx_favicon.png') }}">

    <!-- App css -->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body class="bg-white">
    <!-- Begin page -->
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0">
                <div class="col-xl-5">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                                <div class="mb-4 p-0">
                                    <a href="index.html" class="auth-logo">
                                        <img src="{{ asset('backend/assets/images/inventryx_logo.png') }}" alt="logo-dark" class="mx-auto" height="48" />
                                    </a>
                                </div>

                                <div class="pt-0">
                                    <form method="POST" action="{{ route('register') }}" class="my-4">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input class="form-control" type="text" id="name" name="name" required="" placeholder="Enter your name">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input class="form-control" type="email" id="email" name="email" required="" placeholder="Enter your email">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input class="form-control" type="password" id="password" name="password" required="" placeholder="Enter your password">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required="" placeholder="Confirm your password">
                                        </div>

                                        <div class="form-group d-flex mb-3">
                                            <div class="col-sm-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                                    <label class="form-check-label" for="remember">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <a class='text-muted fs-14' href="{{ route('password.request') }}">Forgot password?</a>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit"> Register </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                    <div class="text-center text-muted mb-4">
                                        <p class="mb-0">Already have an account ?<a class='text-primary ms-2 fw-medium' href="{{ route('login') }}"> login</a></p>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="account-page-bg p-md-5 p-4">
                        <div class="text-center">
                            <h3 class="text-dark mb-3 pera-title">New User? Register Now!</h3>
                            <div class="auth-image">
                                <img src="{{ asset('backend/assets/images/authentication.svg') }}" class="mx-auto img-fluid" alt="images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END wrapper -->

    <!-- Vendor -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

</body>

</html>
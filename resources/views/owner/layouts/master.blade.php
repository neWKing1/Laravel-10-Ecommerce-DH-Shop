<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Bootstrap Icon --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Toastr css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('owner.dashboard') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Manage Product
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('owner.brand.index') }}">Brands</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('owner.product.index') }}">Products</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Manage Category
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('owner.category.index') }}">Category</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('owner.sub-category.index') }}">Sub
                                        Category</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.child-category.index') }}">Child
                                        Category</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Orders
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('owner.order.index') }}">All Order</a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        {{-- Jquery --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- Toastr js --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

        {{-- Sweet Alert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



        <script>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}")
                @endforeach
            @endif
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $('body').on('click', '.delete-item', function(event) {
                    event.preventDefault();
                    let deleteUrl = $(this).attr('href');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            $.ajax({
                                type: 'DELETE',
                                url: deleteUrl,
                                success: function(data) {
                                    console.log(data);
                                    if (data.status == 'success') {
                                        Swal.fire(
                                            'Deleted!',
                                            data.message,
                                            'success'
                                        )
                                        window.location.reload();
                                    } else if (data.status == 'error') {
                                        Swal.fire(
                                            'Cant Delete',
                                            data.message,
                                            'error'
                                        )
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            })
                        }
                    })
                })

            })
        </script>
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        @stack('scripts')
    </div>
</body>

</html>

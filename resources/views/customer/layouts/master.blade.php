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
    <style>
        .dropdown-menu.dropdown-submenu {
            display: none;
        }

        .dropdown:hover>.dropdown-menu,
        .dropdown-menu li:hover .dropdown-menu.dropdown-submenu,
        .dropdown-menu.dropdown-submenu li:hover .dropdown-menu.dropdown-childmenu {
            display: block;
        }

        @media (min-width: 767px) {
            .dropdown-menu.dropdown-submenu {
                display: none;
            }

            .dropdown-menu {
                position: relative;
            }

            .dropdown-menu .dropdown-submenu,
            .dropdown-menu .dropdown-childmenu {
                position: absolute;
                top: 0;
                left: 100%;
                margin-top: -1px;
            }

            .dropdown:hover>.dropdown-menu,
            .dropdown-menu li:hover .dropdown-menu.dropdown-submenu,
            .dropdown-menu.dropdown-submenu li:hover .dropdown-menu.dropdown-childmenu {
                display: block;
            }
        }
    </style>
    {{-- Toastr css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css">
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @php
                        $categories = \App\Models\Category::where('status', 1)
                            ->with([
                                'subCategories' => function ($query) {
                                    $query->where('status', 1)->with([
                                        'childCategories' => function ($query) {
                                            $query->where('status', 1);
                                        },
                                    ]);
                                },
                            ])
                            ->get();
                    @endphp
                    <ul class="navbar-nav me-auto">
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#">
                                Category
                            </a>
                            <ul class="dropdown-menu">
                                <li class=""><a class="dropdown-item" href="#">More Products</a>
                                    <ul class="dropdown-menu dropdown-submenu">
                                        <li><a class="dropdown-item dropdown-subitem" href="#">Sub Product 1</a>
                                            <ul class="dropdown-menu dropdown-childmenu">
                                                <li><a class="dropdown-item dropdown-childitem" href="#">child
                                                        Product 1</a>
                                                </li>
                                                <li><a class="dropdown-item dropdown-childitem" href="#">child
                                                        Product 2</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a class="dropdown-item dropdown-subitem" href="#">Sub Product 2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                            </ul>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#">
                                Category
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($categories as $category)
                                    <li class="{{ count($category->subCategories) > 0 ? 'dropend' : '' }}">
                                        <a href=""
                                            class="dropdown-item {{ count($category->subCategories) > 0 ? 'dropdown-toggle' : '' }}">{{ $category->name }}
                                        </a>
                                        @if (count($category->subCategories) > 0)
                                            <ul class="dropdown-menu dropdown-submenu">
                                                @foreach ($category->subCategories as $subCategory)
                                                    <li
                                                        class="{{ count($subCategory->childCategories) > 0 ? 'dropend' : '' }}">
                                                        <a href=""
                                                            class="dropdown-item dropdown-subitem {{ count($subCategory->childCategories) > 0 ? 'dropdown-toggle' : '' }}">{{ $subCategory->name }}</a>
                                                        @if (count($subCategory->childCategories) > 0)
                                                            <ul class="dropdown-menu dropdown-childmenu">
                                                                @foreach ($subCategory->childCategories as $childCategory)
                                                                    <li><a class="dropdown-item dropdown-childitem"
                                                                            href="#">{{ $childCategory->name }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex mb-3 mb-md-0" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fs-5 px-md-3 position-relative" href="{{ route('cart-details') }}">
                                <i class="bi bi-cart"></i>
                                <span class="badge text-bg-danger position-absolute top-0 start-0"
                                    style="font-size: 10px" id="cart-count">
                                    {{ Cart::content()->count() }}
                                </span>
                            </a>
                        </li>
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
                                <a class="nav-link dropdown-toggle" href="#">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            Orders
                                        </a>
                                    </li>
                                </ul>
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

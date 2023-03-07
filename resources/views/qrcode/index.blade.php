<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ env('SITE_RTL') == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset(url('uploads/logo')) . '/favicon.png' }}" type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'GeniusHr') }}
        - @yield('page-title')</title>

    {{-- font inter --}}
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <!-- Tailwind CSS -->
    <link href="https://unpkg.com/tailwindcss@^2.2.7/dist/tailwind.min.css" rel="stylesheet">
    {{-- owl carousel --}}
    <link rel="stylesheet" href="https://www.jq22.com/demo/OwlCarousel2/owlcarousel/owl.carousel.css">
    <link rel="stylesheet" href="https://www.jq22.com/demo/OwlCarousel2/owlcarousel/owl.theme.css">
    {{-- toastr --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />

    <style>
        .font-inter {
            font-family: 'Inter';
        }

        .owl-item {
            width: 128.906px;
            margin-right: 10px;
            /* background:powderblue;  */
        }

        .owl-item .item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .owl-controls {
            display: none;
        }

        .toast-top-right{
            top: 70px;
            right: 12px;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-100 ">
    <main class="flex-grow">
        <div class="flex bg-blue-500 h-14 items-center justify-center">
            <p class="font-bold text-3xl text-white">{{ $settings['company_name'] }}</p>
        </div>

        <div class="container mx-auto md:px-28 xl:px-28">

            <div>
                <div class="grid grid-cols-1 gap-2 md:grid-cols-5 xl:grid-cols-5 pt-2 pb-2 lg:pt-2"
                    style="cursor: auto;">

                    <div class="col-span-2 m-3 md:m-5 xl:m-5">
                        <div class="bg-white rounded-lg mb-10">

                            <div class="flex items-center h-20 justify-center">
                                <p class="font-bold text-3xl font-inter">
                                    {{ $settings['company_start_time'] . ' - ' . $settings['company_end_time'] }}</p>
                            </div>

                        </div>

                        <div class="flex bg-white rounded-lg items-center justify-center w-auto" id="qr">
                        </div>
                    </div>

                    <div class="col-span-3 m-3 md:m-5 xl:m-5 ">

                        <div class="bg-white rounded-lg mb-10 md:mb-12 xl:mb-12">
                            <div class="flex bg-blue-500 h-10 rounded-t-lg items-center justify-center ">
                                <p class="font-bold text-xl text-white font-inter">Biodata</p>
                            </div>

                            <div class="grid grid-cols-1 gap-1">
                                <div class="p-7">
                                    {{-- <img src="https://mdbootstrap.com/img/new/standard/city/041.jpg" --}}
                                    @if ($last_attendance)
                                        @if ($last_attendance->avatar != null)
                                            {{-- on picture --}}
                                            <img src="{{ asset(url('uploads/avatar/' . $last_attendance->avatar)) }}"
                                                class="rounded-lg md:object-cover xl:object-cover md:mr-10 xl:mr-10 md:h-64 md:w-64 xl:h-64 xl:w-64 md:float-left xl:float-left"
                                                alt="..." id="images" />
                                        @else
                                            {{-- default picture --}}
                                            <img src="{{ asset(url('uploads/avatar/user.png')) }}"
                                                class="rounded-lg md:object-cover xl:object-cover md:mr-10 xl:mr-10 md:h-64 md:w-64 xl:h-64 xl:w-64 md:float-left xl:float-left"
                                                alt="..." id="images" />
                                        @endif
                                    @else
                                        <img src="{{ asset(url('uploads/avatar/user.png')) }}"
                                            class="rounded-lg md:object-cover xl:object-cover md:mr-10 xl:mr-10 md:h-64 md:w-64 xl:h-64 xl:w-64 md:float-left xl:float-left"
                                            alt="..." id="images" />
                                    @endif

                                    <div class="tracking-wide leading-10 text-xl font-inter w-full">
                                        <p class="mb-2" id="name">
                                            @if ($last_attendance)
                                                @php
                                                    $name = explode(' ', $last_attendance->name);
                                                    $name = $name[0] . ' ' . (isset($name[1]) ? $name[1] : '');
                                                @endphp
                                                {{ $name }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <hr>
                                        <p class="mb-2" id="emp_id">
                                            @if ($last_attendance)
                                                {{ $last_attendance->employee_id }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <hr>
                                        <p class="mb-2" id="position">
                                            @if ($last_attendance)
                                                {{ $last_attendance->position }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <hr>
                                        <p class="mb-2" id="time_status">
                                            @if ($last_attendance)
                                                @if (isset($last_attendance->latecharge) and $last_attendance->clock_out != '00:00:00')
                                                    @if ($last_attendance->latecharge->working_late != '00:00:00')
                                                        Presensi Terlambat
                                                        {{ date('H', strtotime($last_attendance->latecharge->working_late)) }}
                                                        Jam
                                                        {{ date('i', strtotime($last_attendance->latecharge->working_late)) }}
                                                        Menit
                                                    @else
                                                        Presensi On Time
                                                    @endif
                                                @else
                                                    @if (strtotime($last_attendance->late) > 0)
                                                        Presensi Terlambat
                                                        {{ (strtotime($last_attendance->late) > 0 ? date('H', strtotime($last_attendance->late)) : 0) }} Jam
                                                        {{ (strtotime($last_attendance->late) > 0 ? date('i', strtotime($last_attendance->late)) : 0) }} Menit
                                                    @else
                                                        Presensi On Time
                                                    @endif
                                                @endif

                                            @else
                                                -
                                            @endif
                                        </p>
                                        <hr>
                                        
                                        <p class="mb-3" id="permission">
                                            @if (!empty($last_attendance->permission))
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-yellow-500 rounded">{{$last_attendance->permission->title}}</span>
                                            @else
                                                -
                                            @endif
                                            
                                        </p>
                                        <hr>
                                        
                                            
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 ">
                            <div class="bg-white mr-3 md:mr-5 xl:mr-5 rounded-lg">
                                <div class="flex bg-blue-500 h-10 rounded-t-lg items-center justify-center ">
                                    <p class="font-bold text-xl text-white font-inter">Jam Masuk</p>
                                </div>

                                <div class="flex items-center h-28 justify-center">
                                    <p class="font-bold text-3xl font-inter" id="clock_in">
                                        @if ($last_attendance)
                                            {{ $last_attendance->clock_in }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white ml-3 md:ml-5 xl:ml-5 rounded-lg">
                                <div class="flex bg-blue-500 h-10 rounded-t-lg items-center justify-center ">
                                    <p class="font-bold text-xl text-white font-inter">Jam Keluar</p>
                                </div>

                                <div class="flex items-center h-28 justify-center">
                                    <p class="font-bold text-3xl font-inter" id="clock_out">
                                        @if ($last_attendance)
                                            {{ $last_attendance->clock_out }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 ml-5 mr-5">

                    <p class="text-xl ml-3 mr-3 mt-3">Today Presence</p>

                    <div class="owl-carousel owl-theme owl-loaded owl-drag">

                        <div class="owl-stage-outer">

                            <div class="owl-stage"
                                style="transform: translate3d(-1527px, 0px, 0px); transition: all 0.25s ease 0s; width: 3334px;"
                                id="today_presence">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="flex mt-5 bg-white h-14 items-center">

        <div class="grid grid-cols-7 gap-7">
            <div class="flex col-span-3 bg-blue-500 h-14 items-center justify-center">
                <img src="{{ asset(url('uploads/logo/' . $settings['company_favicon'])) }}"
                    class="md:object-cover xl:object-cover md:mr-10 xl:mr-10 md:h-10 md:w-10 xl:h-10 xl:w-10 md:float-left xl:float-left"
                    alt="..." id="images" />
                <p class="font-bold text-white">GITS Info</p>
            </div>
            <div class="flex col-span-4 h-14 items-center justify-center">
                <p class="font-sm font-inter">Pengumuman: Presensi On Time Gaise</p>
            </div>
        </div>

        {{-- <div class="flex bg-blue-500 h-14 items-center justify-center">
            <p class="font-bold text-white">GITS Info</p>
        </div>
        <p class="font-sm md:font-lg xl:font-lg font-inter">Pengumuman: Presensi On Time Gaise</p> --}}
    </footer>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- <script src="https://www.jq22.com/demo/OwlCarousel2/assets/js/jquery-1.11.0.min.js"></script> --}}
<script src="https://www.jq22.com/demo/OwlCarousel2/owlcarousel/owl.carousel.js?v=beta.1.8"></script>
<script src="https://www.jq22.com/demo/OwlCarousel2/assets/js/highlight/highlight.pack.js"></script>
{{-- <script src="{{ url('/js/qrcode.js') }}" type="text/javascript"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/gh/maruf-aziz/geniushr/qrcode2.js"></script> --}}
<script>
    let host = '{{ url('/') }}'
    let interval = {{ $settings['autoreload'] }} * 1000;
    window.laravel_echo_port = '{{ env('LARAVEL_ECHO_PORT') }}';
    localStorage.setItem("company_id", {{ $created_by }});
</script>
<script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT') }}/socket.io/socket.io.js"></script>
<script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lrsjng.jquery-qrcode/0.18.0/jquery-qrcode.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script>
    toastr.options.timeOut = 3000;    
</script>
<script src="https://cdn.jsdelivr.net/gh/maruf-aziz/geniushr/qrcode8.js" type="text/javascript"></script>

<script>
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        nav: true,
        loop: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 8
            }
        }
    });

    $(document).ready(function() {


        $.ajax({

            url: host + '/today_presence?created_by=' + localStorage.getItem("company_id"),

            dataType: 'json',

            success: function(data) {

                var content = '';

                for (i in data.data) {

                    content += data.data[i].item;

                }
                owl.trigger('insertContent.owl', content);
                console.log(data.today);
            }

        });

    });
</script>

</html>

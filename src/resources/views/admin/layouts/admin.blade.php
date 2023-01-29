@extends('admin.layouts.main')
@section('content')
    <div id="wrapper">

        @include('admin.elements.side_bar')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('admin.elements.top_bar')

                <div class="container-fluid">

                    @yield('child-content')

                </div>

            </div>

            @include('admin.elements.footer')

        </div>

    </div>
    @include('admin.elements.scroll_top')
    @include('admin.modals.logout')
@endsection







@extends('admin.layouts.main')
@section('content')


    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">THC - Admin Panel</h1>
                                    </div>
                                    @if(session()->has('error'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('error') }}
                                        </div>
                                    @endif
                                    <form class="user" name="loginForm" method="POST"
                                          action="{{route('admin_authenticate')}}">
                                        <div class="form-group">
                                            <input type="email"
                                                   class="form-control form-control-user"
                                                   id="exampleInputEmail"
                                                   aria-describedby="emailHelp"
                                                   placeholder="Username"
                                                   required
                                                   name="email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password"
                                                   class="form-control form-control-user"
                                                   id="exampleInputPassword"
                                                   name="password"
                                                   required
                                                   placeholder="Password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection

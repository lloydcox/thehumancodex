@extends('layouts.user')

@section('main')
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Delete your Account</h5>
            <div class="card">
                <div class="card-header justify-content-between">
                    <h6 class="text-left line-height-none my-auto">Please enter your password to delete your account</h6>
                </div>
                <form name="deleteAccountConfirmationForm" method="POST" action="{{ route('delete_user_account_data') }}">
                    <div class="card-body">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <input type="password" class="form-control" id="password" aria-describedby="password"
                                   placeholder="Enter Your Password" name="password"
                                   value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" style="display: block">
                                <small>{{ $errors->first('password') }}</small>
                            </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-outline-primary btn-sm">Delete My Account</button>
                    </div>
                </form>
            </div>
        </section>
    </section>
@endsection

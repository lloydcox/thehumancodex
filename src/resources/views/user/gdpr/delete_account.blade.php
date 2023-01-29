@extends('layouts.user')

@section('main')
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Delete your Account</h5>
            <div class="card">
                <div class="card-header justify-content-between">
                    <h6 class="text-left line-height-none my-auto">Are you sure you want to delete your account?</h6>
                </div>
                <div class="card-body">
                    <p class="text-small-para">
                        If you want to permanently delete your THC account, let us know.
                        Once the deletion process begins, you won't be able to reactivate your account
                        or retrieve any of the content or information you have added.
                    </p>
                    <p class="text-small-para mt-3">
                        If you want to save your information before your account and content is permanently deleted, you can
                        <a href="{{route('download_data')}}">Download a copy</a>  of your information.</p>
                    <br>
                    <a href="{{route('delete_user_account_data_confirm')}}">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Delete My Account</button>
                    </a>
                </div>
            </div>
        </section>
    </section>
@endsection

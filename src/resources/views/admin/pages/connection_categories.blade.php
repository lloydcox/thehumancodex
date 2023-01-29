@extends('admin.layouts.admin')
@section('child-content')
    <h1 class="h3 mb-4 text-gray-800">Connection Categories</h1>
    <p class="mb-4">Customize all connection catgories of THC platform</p>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="m-0 font-weight-bold text-primary">All Connection Categories</h3>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{route('admin_connection_categories_form')}}" class="btn btn-sm btn-primary">Add New</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($connection_categories as $index => $connection_category)
                    <tr>
                        <td>{{$connection_category->id}}</td>
                        <td>{{$connection_category->title}}</td>
                        <td>{{$connection_category->description ? $connection_category->description : 'N / A' }}</td>
                        <td style="{{'background-color:'.$connection_category->color_code.'; color:darkblue'}}">{{$connection_category->color_code}}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#connectionCategoryDeleteConfirmationModal"
                                    data-id="{{$connection_category->id}}">
                                Delete
                            </button>
                            <a href="{{ '/admin/connection-categories/edit/'.$connection_category->id }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.modals.connection_category_delete_confirmation');
@endsection

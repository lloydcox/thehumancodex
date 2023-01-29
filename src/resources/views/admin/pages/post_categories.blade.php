@extends('admin.layouts.admin')
@section('child-content')
    <h1 class="h3 mb-4 text-gray-800">Post Categories</h1>
    <p class="mb-4">Customize all post catgories of THC platform</p>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="m-0 font-weight-bold text-primary">All Post Categories</h3>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{route('admin_post_categories_form')}}" class="btn btn-sm btn-primary">Add New</a>
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
                    @foreach($post_categories as $index => $post_category)
                    <tr>
                        <td>{{$post_category->id}}</td>
                        <td>{{$post_category->title}}</td>
                        <td>{{$post_category->description ? $post_category->description : 'N / A' }}</td>
                        <td style="{{'background-color:'.$post_category->color_code.'; color:darkblue'}}">{{$post_category->color_code}}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#postCategoryDeleteConfirmationModal"
                                    data-id="{{$post_category->id}}">
                                Delete
                            </button>
                            <a href="{{ '/admin/post-categories/edit/'.$post_category->id }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.modals.post_category_delete_confirmation');
@endsection

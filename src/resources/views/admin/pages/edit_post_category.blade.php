@extends('admin.layouts.admin')
@section('child-content')
    <h1 class="h3 mb-4 text-gray-800">Post Categories</h1>
    <p class="mb-4">Customize all post categories of THC platform</p>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-primary">Edit Post Category</h3>
        </div>
        <form name="editPostCategoryForm" method="POST" action="{{route('admin_edit_post_categories_put')}}" enctype="multipart/form-data">
            <div class="card-body">
                <input type="hidden" value="{{csrf_token()}}" name="_token">
                <input type="hidden" value="{{$postCategory->id}}" name="id">

                @if ($errors->any())
                    <div class="alert alert-danger text-center">
                        <strong>Error!</strong> Please fix the following errors
                    </div>
                @endif

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="title" aria-describedby="title"
                           placeholder="Give a title for your Post Category" name="title"
                           value="{{ old('title') ? old('title') : $postCategory->title !== null ? $postCategory->title : '' }}">
                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display: block">
                                <small>{{ $errors->first('title') }}</small>
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="3" name="description"
                              placeholder="Give a description for your Post Category, if you have one">{{ old('description') ? old('description') : $postCategory->description !== null ? $postCategory->description : '' }}</textarea>
                    @if ($errors->has('description'))
                        <span class="invalid-feedback" style="display: block">
                                <small>{{ $errors->first('description') }}</small>
                            </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="color_code">Color <span class="text-danger">*</span> </label>
                    <input type="color" class="form-control" id="color_code"
                           name="color_code" value="{{ old('color_code') ? old('color_code') : $postCategory->color_code !== null ? $postCategory->color_code : '#000000' }}">
                    @if ($errors->has('color_code'))
                        <span class="invalid-feedback" style="display: block">
                                <small>{{ $errors->first('color_code')}}</small>
                            </span>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="reset" class="btn btn-info">Reset</button>
                <a href="{{route('admin_post_categories')}}" class="btn btn-secondary">Back To All Post Categories</a>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.public')

@section('title', 'Bug Report')

@section('main')
    <div class="container jumbotron jumbotron-fluid">
        <div class="container-fluid col-9 ">
            <!--bug report form-->
            <form method="post" action="" >
                {{ csrf_field() }}
                <!--title-->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" placeholder="Title">
                    </div>
                </div>
                <!--description-->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea type="text" rows="5" name="description" class="form-control"></textarea>
                    </div>
                </div>

                <!--Attachments-->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Attachment</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input"  name="choose" required>
                            <label class="custom-file-label" >Choose file...</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label "></label>
                    <div class="col-sm-4">
                        <div class="custom-file">
                            <label class="col-sm-9 col-form-label attach-lable">Attachment 01<i  class="fas fa-times p-1 icon-attachment"></i></label>
                        </div>
                    </div>


                    <label class=" col-form-label "></label>
                    <div class="col-sm-4">
                        <div class="custom-file">
                            <label class="col-sm-9 col-form-label attach-lable">Attachment 02<i class="fas fa-times p-1 icon-attachment"></i></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label "></label>
                    <div class="col-sm-4">
                        <div class="custom-file">
                            <label class="col-sm-9 col-form-label attach-lable">Attachment 03<i class="fas fa-times p-1 icon-attachment"></i></label>
                        </div>
                    </div>
                </div>


                <!--Button Sets-->
                <div class="form-group row">
                    <div class="col ">
                        <div class="btn-location">
                            <button type="submit" class="btn btn-primary col-5">Submit</button>
                            <button type="button" class="btn btn col-5 btn-reset">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')

@endsection

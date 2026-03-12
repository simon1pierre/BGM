@extends('layouts.admin.app')

@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Ministry Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ministry-leaders.index') }}">Ministry Leaders</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ministry-leaders.store') }}" enctype="multipart/form-data">
                        @csrf
                        @include('Admin.MinistryLeaders.partials.form')

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Profile</button>
                            <a href="{{ route('admin.ministry-leaders.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




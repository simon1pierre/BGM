@extends('layouts.admin.app')

@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Ministry Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ministry-leaders.index') }}">Ministry Leaders</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ministry-leaders.update', $ministryLeader) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('Admin.MinistryLeaders.partials.form')

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="{{ route('admin.ministry-leaders.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


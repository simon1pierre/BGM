@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Devotional</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.devotionals.index') }}">Devotionals</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <div class="fw-semibold mb-2">Please fix the errors below:</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.devotionals.update', $devotional) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('Admin.Content.Devotionals._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

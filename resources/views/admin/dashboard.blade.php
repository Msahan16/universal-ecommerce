@extends('layouts.app')

@section('content')

<div class="container py-5">

    <h1 class="mb-4">
        Admin Dashboard
    </h1>

    <div class="alert alert-success">
        Welcome to the administration panel.
    </div>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Products</h5>
                    <h2>0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Orders</h5>
                    <h2>0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Customers</h5>
                    <h2>0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Sales</h5>
                    <h2>Rs. 0</h2>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
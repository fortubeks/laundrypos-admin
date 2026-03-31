@extends('material.layouts.app')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Customer Details</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="mb-3">{{ $customer->name }}</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0"><strong>Email:</strong> {{ $customer->email }}
                                            </li>
                                            <li class="list-group-item px-0"><strong>Phone:</strong> {{ $customer->phone }}
                                            </li>
                                            <li class="list-group-item px-0"><strong>Laundry:</strong>
                                                {{ $customer->laundry ? $customer->laundry->name : '-' }}</li>
                                            <li class="list-group-item px-0"><strong>Address:</strong>
                                                {{ $customer->address }}</li>
                                            <li class="list-group-item px-0"><strong>Gender:</strong>
                                                {{ $customer->gender }}</li>
                                            <li class="list-group-item px-0"><strong>Country:</strong>
                                                {{ $customer->country ? $customer->country->name : '-' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-3">Orders</h6>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Amount</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Date</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ $order->total_amount }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="btn btn-outline-primary btn-sm">View Order</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

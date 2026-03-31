@extends('material.layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-gradient-dark text-white">
                        <h5 class="mb-0">Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0"><strong>Order ID:</strong> {{ $order->id }}</li>
                                    <li class="list-group-item px-0"><strong>Status:</strong> <span
                                            class="badge bg-gradient-primary text-white text-capitalize">{{ $order->status ?? 'N/A' }}</span>
                                    </li>
                                    <li class="list-group-item px-0"><strong>Order Type:</strong>
                                        {{ $order->order_type ?? 'N/A' }}</li>
                                    <li class="list-group-item px-0"><strong>Total Amount:</strong>
                                        {{ $user && $user->appSetting && $user->appSetting->business_currency ? $user->appSetting->business_currency : 'NGN' }}{{ number_format($order->total_amount, 2) }}
                                    </li>
                                    <li class="list-group-item px-0"><strong>Payment Status:</strong>
                                        {{ $order->payment_status ?? 'N/A' }}</li>
                                    <li class="list-group-item px-0"><strong>Delivery Status:</strong>
                                        {{ $order->delivery_status ?? 'N/A' }}</li>
                                    <li class="list-group-item px-0"><strong>Created At:</strong>
                                        {{ $order->created_at ? $order->created_at->format('d F Y, h:i A') : 'N/A' }}</li>
                                    <li class="list-group-item px-0"><strong>Last Updated:</strong>
                                        {{ $order->updated_at ? $order->updated_at->format('d F Y, h:i A') : 'N/A' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0"><strong>Customer:</strong>
                                        @if ($order->customer)
                                            <a
                                                href="{{ route('customers.show', $order->customer->id) }}">{{ $order->customer->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </li>
                                    <li class="list-group-item px-0"><strong>Laundry:</strong>
                                        {{ $order->laundry ? $order->laundry->name : 'N/A' }}</li>
                                    <li class="list-group-item px-0"><strong>Notes:</strong> {{ $order->notes ?? 'N/A' }}
                                    </li>
                                    <!-- Add more order-specific fields here if needed -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xl-6 mb-xl-0 mb-4">
                        <div class="card shadow-xl pb-0 p-3">
                            <div class="row gx-4 mb-2">
                                <div class="col-auto">
                                    <div class="avatar avatar-xl position-relative">
                                        <img src="https://app.handyseam.com/storage/logo_images/{{ $user && $user->appSetting && $user->appSetting->business_logo ? $user->appSetting->business_logo : 'default.png' }}"
                                            alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                                    </div>
                                </div>
                                <div class="col-auto my-auto">
                                    <div class="h-100">
                                        <h5 class="mb-1">
                                            {{ $user->name }}
                                        </h5>
                                        <p class="mb-0 font-weight-normal text-sm">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                    <hr class="horizontal gray-light my-4">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong
                                                class="text-dark">Business Name:</strong> &nbsp;
                                            {{ $user && $user->appSetting && $user->appSetting->business_name ? $user->appSetting->business_name : 'N/A' }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Currency:</strong> &nbsp;
                                            {{ $user && $user->appSetting && $user->appSetting->business_currency ? $user->appSetting->business_currency : 'NGN' }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Phone:</strong> &nbsp; {{ $user->phone }}</li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Location:</strong> &nbsp;
                                            {{ $user && $user->appSetting && $user->appSetting->business_address ? $user->appSetting->business_address : 'N/A' }}
                                        </li>
                                        @if (isset($order) && $order->customer)
                                            <li class="list-group-item border-0 ps-0 text-sm">
                                                <strong class="text-dark">Customer:</strong>
                                                <a
                                                    href="{{ route('customers.show', $order->customer->id) }}">{{ $order->customer->name }}</a>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-md-4 col-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div
                                            class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                            <i class="material-symbols-rounded opacity-10">account_balance</i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0">Orders</h6>
                                        <span class="text-xs">Belong Interactive</span>
                                        <hr class="horizontal dark my-3">
                                        <h5 class="mb-0">{{ $user->orders->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div
                                            class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                            <i class="material-symbols-rounded opacity-10">account_balance_wallet</i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0">Lifetime Value</h6>
                                        <span class="text-xs">Freelance Payment</span>
                                        <hr class="horizontal dark my-3">
                                        <h5 class="mb-0">{{ formatCurrency($ltv) }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-4">
                                <div class="card">
                                    <div class="card-header mx-4 p-3 text-center">
                                        <div
                                            class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                            <i class="material-symbols-rounded opacity-10">account_balance_wallet</i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 p-3 text-center">
                                        <h6 class="text-center mb-0">Customers</h6>
                                        <span class="text-xs">Freelance Payment</span>
                                        <hr class="horizontal dark my-3">
                                        <h5 class="mb-0">{{ $user->customers->count() }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">User Metrics</h6>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-outline-primary btn-sm mb-0">View All</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Average Orders Monthly</h6>
                                    <span class="text-xs">#MS-415646</span>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $averageOrdersPerMonth }}
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Last Order Date</h6>
                                    <span class="text-xs">#MS-415646</span>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $latestOrder ? $latestOrder->created_at->format('d F Y') : 'N/A' }}
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Last Login Date</h6>
                                    <span class="text-xs">#MS-415646</span>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $user->last_login ? $user->last_login : 'N/A' }}
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Total Orders Amount</h6>
                                    <span class="text-xs">#MS-415646</span>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $user && $user->appSetting && $user->appSetting->business_currency ? $user->appSetting->business_currency : 'NGN' }}{{ number_format($totalOrdersAmount, 2) }}
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Last 5 Orders</h6>
                            </div>
                            <div class="col-6 text-end">
                                <button class="btn btn-outline-primary btn-sm mb-0">View All</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            @foreach ($lastFiveOrders as $order)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">
                                            {{ $order->created_at->format('d F Y') }}</h6>
                                        <span class="text-xs">{{ $order->order_type }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-sm">
                                        {{ $user && $user->appSetting && $user->appSetting->business_currency ? $user->appSetting->business_currency : 'NGN' }}{{ number_format($order->total_amount, 2) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0">Subscriptions</h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-start justify-content-md-end align-items-center">
                                <i class="material-symbols-rounded me-2 text-lg">date_range</i>
                                <small>since {{ $user->created_at->format('d F Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <!-- Subscriptions section removed to fix undefined variable error -->
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    window.addEventListener('load', function() {

    });
</script>

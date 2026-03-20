@extends('material.layouts.app')
@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-xl-6 mb-xl-0 mb-4">
                        <div class="card shadow-xl pb-0 p-3">
                            <div class="row gx-4 mb-2">
                                <div class="col-auto">
                                    <div
                                        class="avatar avatar-xl border-radius-lg bg-gradient-dark text-white d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                </div>
                                <div class="col-auto my-auto">
                                    <div class="h-100">
                                        <h5 class="mb-1">{{ $user->name }}</h5>
                                        <p class="mb-0 font-weight-normal text-sm">{{ $user->email }}</p>
                                    </div>
                                    <hr class="horizontal gray-light my-3">
                                    <ul class="list-group">
                                        <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong
                                                class="text-dark">Business Name:</strong>
                                            {{ $user->appSetting?->business_name ?? ($user->laundries->first()->name ?? 'N/A') }}
                                        </li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Currency:</strong>
                                            {{ $user->appSetting?->business_currency ?? 'NGN' }}</li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Phone:</strong> {{ $user->phone ?? 'N/A' }}</li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Location:</strong>
                                            {{ $user->appSetting?->business_address ?? 'N/A' }}</li>
                                        <li class="list-group-item border-0 ps-0 text-sm"><strong
                                                class="text-dark">Role:</strong> {{ $user->role ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="text-center mb-0">Orders</h6>
                                        <hr class="horizontal dark my-2">
                                        <h5 class="mb-0">{{ $user->orders_count }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="text-center mb-0">Customers</h6>
                                        <hr class="horizontal dark my-2">
                                        <h5 class="mb-0">{{ $user->customers_count }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="text-center mb-0">Laundries</h6>
                                        <hr class="horizontal dark my-2">
                                        <h5 class="mb-0">{{ $user->laundries_count }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="text-center mb-0">LTV</h6>
                                        <hr class="horizontal dark my-2">
                                        <h5 class="mb-0">{{ formatCurrency($ltv) }}</h5>
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
                        <h6 class="mb-0">User Metrics</h6>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Average Orders Monthly</h6>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $averageOrdersPerMonth }}</div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Last Order Date</h6>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $latestOrder ? $latestOrder->created_at->format('d F Y') : 'N/A' }}</div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Last Login</h6>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $user->last_login ?? 'N/A' }}</div>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark font-weight-bold text-sm">Total Orders Amount</h6>
                                </div>
                                <div class="d-flex align-items-center font-weight-bold text-sm">
                                    {{ $user->appSetting?->business_currency ?? 'NGN' }}{{ number_format($totalOrdersAmount, 2) }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Last 5 Orders</h6>
                        <a href="{{ url('orders?user_id=' . $user->id) }}" class="btn btn-outline-primary btn-sm mb-0">View
                            All</a>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group">
                            @forelse($lastFiveOrders as $order)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">
                                            {{ $order->created_at->format('d F Y') }}</h6>
                                        <span class="text-xs text-capitalize">{{ $order->status }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-sm">
                                        {{ $user->appSetting?->business_currency ?? 'NGN' }}{{ number_format($order->total_amount, 2) }}
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item border-0 ps-0 text-sm">No orders found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Order Status Analytics</h6>
                    </div>
                    <div class="card-body p-3 pb-0">
                        <ul class="list-group mb-3">
                            @forelse($orderStatusBreakdown as $status => $count)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <span class="text-sm text-capitalize">{{ $status }}</span>
                                    <strong class="text-sm">{{ $count }}</strong>
                                </li>
                            @empty
                                <li class="list-group-item border-0 ps-0 text-sm">No status data available.</li>
                            @endforelse
                        </ul>

                        <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-2">Recent Laundries</h6>
                        <ul class="list-group">
                            @forelse($recentLaundries as $laundry)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $laundry->name }}</h6>
                                        <span class="text-xs">{{ $laundry->phone ?? 'No phone' }}</span>
                                    </div>
                                    <span class="text-xs">{{ $laundry->created_at?->format('d M Y') }}</span>
                                </li>
                            @empty
                                <li class="list-group-item border-0 ps-0 text-sm">No laundries found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

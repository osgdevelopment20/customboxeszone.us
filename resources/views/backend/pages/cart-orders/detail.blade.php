@extends('backend.layouts.app')
@section('title')
    Order {{ $order->order_number }}
@endsection
@section('contents')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">
                            Order {{ $order->order_number }}
                        </h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard.cart-orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0"><i class="ri-user-line me-2"></i>Customer Details</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 vstack gap-3">
                                        <li>
                                            <strong>Name:</strong><br>
                                            <i class="ri-user-2-line me-2 align-middle text-muted fs-16"></i>{{ $order->name }}
                                        </li>
                                        <li>
                                            <strong>Email:</strong><br>
                                            <i class="ri-mail-line me-2 align-middle text-muted fs-16"></i>
                                            <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>
                                        </li>
                                        <li>
                                            <strong>Phone:</strong><br>
                                            <i class="ri-phone-line me-2 align-middle text-muted fs-16"></i>
                                            <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a>
                                        </li>
                                        <li>
                                            <strong>Placed At:</strong><br>
                                            <i class="ri-calendar-line me-2 align-middle text-muted fs-16"></i>
                                            {{ date_format($order->created_at, "g:ia \o\n l jS F Y") }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0"><i class="ri-file-list-3-line me-2"></i>Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-3 vstack gap-3">
                                        <li><strong>Products:</strong> {{ $order->items_count }}</li>
                                        <li><strong>Total Units:</strong> {{ number_format($order->total_quantity) }}</li>
                                        <li>
                                            <strong>Location:</strong>
                                            {{ collect([$order->city, $order->region, $order->country, $order->postal])->filter()->implode(', ') ?: 'N/A' }}
                                        </li>
                                        <li><strong>IP Address:</strong> {{ $order->ip_address ?: 'N/A' }}</li>
                                    </ul>

                                    <form action="{{ route('dashboard.cart-orders.status', $order->id) }}" method="POST"
                                          class="d-flex align-items-center gap-2">
                                        @csrf
                                        <label for="status" class="form-label mb-0"><strong>Status:</strong></label>
                                        <select name="status" id="status" class="form-select form-select-sm w-auto">
                                            @foreach (['pending', 'contacted', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" @selected($order->status === $status)>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0"><i class="ri-sticky-note-line me-2"></i>Order Notes</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="card border">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0"><i class="ri-shopping-bag-line me-2"></i>Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 90px;">Image</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Dimensions</th>
                                            <th>Quantity</th>
                                            <th>Artwork</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->image)
                                                        <img src="{{ $item->image }}" alt="{{ $item->product_name }}"
                                                             width="64" height="64" style="object-fit: contain;">
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->product_slug)
                                                        <a href="{{ route('page', $item->product_slug) }}" target="_blank" rel="noopener">
                                                            {{ $item->product_title ?: $item->product_name }}
                                                        </a>
                                                    @else
                                                        {{ $item->product_title ?: $item->product_name }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->category ?: '—' }}</td>
                                                <td>{{ $item->dimensions ?: 'Standard' }}</td>
                                                <td>{{ number_format($item->quantity) }}</td>
                                                <td>
                                                    @if (!empty($item->artwork))
                                                        @foreach ($item->artwork as $file)
                                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                               rel="noopener" class="d-block">
                                                                <i class="ri-attachment-2 align-middle me-1"></i>{{ basename($file) }}
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
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
    </div>
@endsection

@extends('orders::components.layouts.master')

@section('content')
<h1>Payment Success</h1>
<p>Order: {{ $order->order_code }}</p>
<p>Status: {{ $order->status }}</p>
<p>Total: {{ number_format($order->total,2) }} {{ $order->currency }}</p>
@endsection

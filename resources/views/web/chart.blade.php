@extends('ui.base')
@section('title')
    Home
@endsection
@section('content')
    <a href="{{ url('/product-web') }}" class="text-gray-500 mb-4 inline-block">&lt; Kembali</a>
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>
    <div class="flex flex-col lg:flex-row lg:space-x-8">
        <div class="w-full lg:w-2/3 mb-8 lg:mb-0">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-left">Produk</th>
                        <th class="py-3 px-4 text-left">Harga</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Total</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="py-3 px-4">Produk 1</td>
                        <td class="py-3 px-4">Rp 100.000</td>
                        <td class="py-3 px-4 flex items-center">
                            <button class="px-2 py-1 bg-gray-200 rounded">-</button>
                            <span class="mx-2">2</span>
                            <button class="px-2 py-1 bg-gray-200 rounded">+</button>
                        </td>
                        <td class="py-3 px-4">Rp 200.000</td>
                        <td class="py-3 px-4 text-red-500"><i class="fas fa-trash-alt"></i></td>
                    </tr>
                    <tr class="border-t">
                        <td class="py-3 px-4">Produk 2</td>
                        <td class="py-3 px-4">Rp 150.000</td>
                        <td class="py-3 px-4 flex items-center">
                            <button class="px-2 py-1 bg-gray-200 rounded">-</button>
                            <span class="mx-2">1</span>
                            <button class="px-2 py-1 bg-gray-200 rounded">+</button>
                        </td>
                        <td class="py-3 px-4">Rp 150.000</td>
                        <td class="py-3 px-4 text-red-500"><i class="fas fa-trash-alt"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span>Rp 350.000</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Pengiriman</span>
                    <span>Gratis</span>
                </div>
                <div class="flex justify-between font-bold text-lg mb-4">
                    <span>Total</span>
                    <span>Rp 350.000</span>
                </div>
                <button class="w-full bg-pink-400 text-white py-2 rounded">Checkout</button>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection

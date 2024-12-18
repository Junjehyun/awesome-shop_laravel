@extends('layouts.shop_common')
@section('title', 'Shop Index')
@section('content')
    <div class="flex justify-end mb-4 space-x-5">
        <a href="{{ route('item.regIndex') }}">商品登録</a>
        <a href="/main_index">MAIN</a>
    </div>
    <div class="flex flex-row items-center justify-center space-x-2 mt-5">
        <a href="javascript:void(0)" class="text-center text-sm py-1 px-1 rounded-xl category-btn" data-category="ALL">・ ALL ITEMS</a>
        <a href="javascript:void(0)" class="text-center text-sm py-1 px-1 rounded-xl category-btn" data-category="상의">・ TOP</a>
        <a href="javascript:void(0)" class="text-center text-sm py-1 px-1 rounded-xl category-btn" data-category="하의">・ BOTTOM</a>
        <a href="javascript:void(0)" class="text-center text-sm py-1 px-1 rounded-xl category-btn" data-category="신발">・ SHOES</a>
        <a href="javascript:void(0)" class="text-center text-sm py-1 px-1 rounded-xl category-btn" data-category="ETC">・ ETC</a>
        <a href="javascript:void(0)" class="text-center text-sm text-red-500 font-semibold category-btn" data-category="SALE">・ SALE</a>
        <div class="flex justify-items-end my-4">
            <form action="{{ route('item.search') }}" method="GET" class="flex items-center space-x-1 ml-52">
                <!-- focus 파란색깔 안없어짐 작업해야함 -->
                <input type="text" name="keyword" placeholder="商品検索" class="px-3 py-2 border border-gray-200 rounded-md w-64" value="{{ old('keyword', $keyword ?? '') }}">
                <button type="submit" class="px-3 py-2 outline outline-gray-200 rounded-xl">検索</button>
            </form>
        </div>
    </div>
    <div class="flex flex-row items-center justify-center space-x-5 mt-5">
        <div class="grid grid-cols-4 gap-5">
        @forelse ($items as $item)
            <div class="border rounded-lg overflow-hidden shadow-md">
                <div class="flex justify-center">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : 'images/default-image.png' }}" alt="{{ $item->name }}" class="h-48 w-48 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold">{{ $item->name }}</h3>
                    <p class="text-gray-700 mt-1">{{ number_format($item->price) }}円</p>
                    <div class="mt-3 flex justify-between items-center">
                        <a href="/item/detail/{{ $item->id }}" class="px-2 py-1 outline outline-gray-200 rounded-xl">詳細</a>
                        <div class="space-x-1">
                            <button  onclick="addToCart('{{ $item->id }}')" class="px-3 py-1 outline outline-gray-200 rounded-xl">カート</button>
                            <button class="px-3 py-1 outline outline-gray-200 rounded-xl">購入</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center text-xl mt-10">
                @if (!empty($keyword))
                    <p class="text-gray-700">検索結果がございません。</p>
                @else
                    <p class="text-gray-700">登録された商品がございません。</p>
                @endif
            </div>
        @endforelse
    </div>
    <script>
        $(document).ready(function () {
            $('.category-btn').on('click', function () {
                const category = $(this).data('category');
                console.log(category);
                $.ajax({
                    url: `/item/ajax_category/${category}`,
                    type: 'GET',
                    success: function (data) {
                        renderItems(data);
                    },
                    error: function () {
                        alert('エラーが発生しました。もう一度やり直してください!');
                    }
                });
            });
            function renderItems(items) {
                const container = $('.grid');
                container.empty();
                if (items.length > 0) {
                    items.forEach(item => {
                        const itemHtml = `
                            <div class="border rounded-lg overflow-hidden shadow-md">
                                <div class="flex justify-center">
                                    <img src="${item.image ? `/storage/${item.image}` : 'images/default-image.png'}" alt="${item.name}" class="h-48 w-48 object-cover">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">${item.name}</h3>
                                    <p class="text-gray-700 mt-1">${item.price.toLocaleString()}円</p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <a href="/item/detail/${item.id}" class="px-2 py-1 outline outline-gray-200 rounded-xl">詳細</a>
                                        <div class="space-x-1">
                                            <button onclick="addToCart(${item.id})" class="px-3 py-1 outline outline-gray-200 rounded-xl">カート</button>
                                            <button class="px-3 py-1 outline outline-gray-200 rounded-xl">購入</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(itemHtml);
                    });
                } else {
                    container.append('<div class="col-span-4 text-center text-xl mt-10"><p class="text-gray-700">登録された商品がございません。</p></div>');
                }
            }
        });
    </script>


@endsection

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.user_orders.index') }}"> @lang('site.orders')</a></li>
                <li class="active">@lang('site.add')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.user_orders.store') }}" method="post" enctype="multipart/form-data">

                        @csrf

                      
                        

                        
                            <div class="form-group">
                                <label for="">Order Number</label>
                                <input type="text" name="order_number" class="form-control" value="{{ ($order->order_number ?? 0) + 1 }}"
                                >
                            </div>
                            <div class="form-group">
                                <label for="">Table Number</label>
                                <input type="text" class="form-control" name="table_number" >
                            </div>
                            <div class="form-group">
                                <label for="">Products</label>
                                <select id="products" class="form-control" name="products[]" multiple>
                                    <option disabled >اختر الطلب</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- <div class="form-group">
                                <label for="">Total Price</label>
                                <input type="number" id="total_price" class="form-control" name="total_price" readonly>
                            </div> --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsSelect = document.getElementById('products');
        const totalPriceInput = document.getElementById('total_price');

        productsSelect.addEventListener('change', function () {
            let total = 0;

            // هنمشي على كل عنصر متعلم عليه
            for (let option of this.selectedOptions) {
                let price = parseFloat(option.getAttribute('data-price'));
                if (!isNaN(price)) {
                    total += price;
                }
            }

            totalPriceInput.value = total;
        });
    });
</script>

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.user_orders.index') }}"> @lang('site.orders')</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.user_orders.update', $order->id) }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                   
                        <div class="form-group">
                            <label for="">Order Number</label>
                            <input type="text" name="order_number" class="form-control" value="{{ $order->order_number }}">
                        </div>
                        <div class="form-group">
                            <label for="">Table Number</label>
                            <input type="text" class="form-control" name="table_number" value="{{ $order->table_number }}">
                        </div>
                        <div class="form-group">
                            <label for="">Products</label>
                            <select id="products" class="form-control" name="products[]" multiple>
                                <option disabled >اختر الطلب</option>
                                @foreach ($products as $product)
                                <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->sale_price }}"
                                {{ in_array($product->id, $order->products ) ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            
                                @endforeach
                            </select>
                        </div>
                     

                      

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.orders') <small>{{ $orders->total() }}</small></h3>

                    <form action="{{ route('dashboard.user_orders.create') }}" method="get">

                        <div class="row">

                            {{-- <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div> --}}

                           
                            
                            <div class="col-md-4">
                                {{-- <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button> --}}
                                @if (auth()->user()->hasPermission('orders-create'))
                                    <a href="{{ route('dashboard.user_orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('site.user_order_number') }} </th>
                                <th> {{ __('site.user_order_table') }}</th>
                                <th>{{ __('site.user_order_total') }}</th>
                            </tr>
                            </thead>
                            
                            <tbody>
                            @foreach ($orders as $index=>$order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->table_number }}</td>
                                    <td>{{ $order->total_price }}</td>
                                    <td>
                                        @if (auth()->user()->hasPermission('orders-update'))
                                            <a href="{{ route('dashboard.user_orders.edit', $order->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif
                                        @if (auth()->user()->hasPermission('orders-delete'))
                                            <form action="{{ route('dashboard.user_orders.destroy', $order->id) }}" method="post" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif
                                    </td>
                                </tr>
                            
                            @endforeach
                            </tbody>

                        </table><!-- end of table -->
                        
                        {{ $orders->appends(request()->query())->links() }}
                        
                    @else
                        
                        <h2>@lang('site.no_data_found')</h2>
                        
                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection

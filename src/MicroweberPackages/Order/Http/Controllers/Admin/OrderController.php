<?php

namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public function index(Request $request)
    {
        $filteringResults = false;

        $orderBy = $request->get('orderBy', 'id');
        $orderDirection = $request->get('orderDirection', 'desc');

        $keyword = $request->get('keyword', '');
        if (!empty($keyword)) {
            $filteringResults = true;
        }

        $newOrders = Order::filter($request->all())->where('order_status','new')->get();

        $orders = Order::filter($request->all())
            ->where('order_status', '!=', 'new')
            ->paginate($request->get('limit', 15))
            ->appends($request->except('page'));

        return $this->view('order::admin.orders.index', [
            'orderBy'=>$orderBy,
            'orderDirection'=>$orderDirection,
            'filteringResults'=>$filteringResults,
            'keyword'=>$keyword,
            'newOrders'=>$newOrders,
            'orders'=>$orders
        ]);
    }

    public function show($id)
    {
        $order = Order::where('id',$id);

        return $this->view('order::admin.orders.show', [
            'order'=>$order
        ]);
    }
}
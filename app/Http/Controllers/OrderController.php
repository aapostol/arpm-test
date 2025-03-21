<?php
namespace App\Http\Controllers;

use App\Actions\TaskTwoAction;
use App\Models\Order;
use App\Models\CartItem;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => (new TaskTwoAction())->execute()
        ]);
    }
}

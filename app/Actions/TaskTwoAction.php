<?php

namespace App\Actions;

class TaskTwoAction
{
    public function execute(): array
    {
        $orders = Order::all();

        $data = $orders
            ->maps(fn (Order $order) => $this->constructDataItemFromOrder($order))
            ->toArray();

        $this->sortOrdersByCompletionDate($data);

        return $data;
    }

    private function constructDataItemFromOrder(Order $order): array
    {
        $customer = $order->customer;
        $itemsCount = $order->items->count();
        $totalAmount = $order
            ->items
            ->reduce(fn ($carry, $item) => $item->price * $item->quantity);;

        $lastAddedToCart = $order->cartItems()
            ->orderByDesc('created_at')
            ->first()
            ?->created_at;

        $isOrderCompleted = $order->satuts === 'completed';

        return [
            'order_id' => $order->id,
            'customer_name' => $customer->name,
            'total_amount' => $totalAmount,
            'items_count' => $itemsCount,
            'last_added_to_cart' => $lastAddedToCart,
            'is_order_complete' => $isOrderCompleted,
            'completed_at' => $isOrderCompleted && ! is_null($order->completed_at) ? $order->completed_at : null,
            'created_at' => $order->created_at,
        ];
    }

    /**
     * @param array{array{completed_at: ?string}} $data
     */
    private function sortOrdersByCompletionDate(array $data): void
    {
        usort($data, function (array $a, array $b) {
            return strtotime($b['completed_at']) - strtotime($a['completed_at']);
        });
    }
}

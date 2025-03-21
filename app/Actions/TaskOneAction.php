<?php

namespace App\Actions;

class TaskOneAction
{
    public function execute(): array
    {
        $weeks = 52;
        $people = 10;

        $headers = collect()->pad($weeks, null)
            ->map(fn ($value, $week) => "Week $week")
            ->prepend('People')
            ->toArray();

        $data[] = $headers;

        for ($i = 1; $i <= $people; $i++) {
            $row = [];
            $row[] = "John Doe $i";
            $sum = 0;

            for ($j = 0; $j < $weeks; $j++) {
                $num = mt_rand() / mt_getrandmax();
                $sum += $num;
                $row[] = number_format($sum, 3);
            }
            $data[] = $row;
        }

        return $data;
    }
}

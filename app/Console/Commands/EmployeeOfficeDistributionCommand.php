<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class EmployeeOfficeDistributionCommand extends Command
{
    protected $signature = 'app:employee-office-distribution-command';

    protected $description = 'This command distributes the employees to the appropriate office';

    public function handle()
    {
        $employees = [
            ['name' => 'John', 'city' => 'Dallas'],
            ['name' => 'Jane', 'city' => 'Austin'],
            ['name' => 'Jake', 'city' => 'Dallas'],
            ['name' => 'Jill', 'city' => 'Dallas'],
        ];

        $offices = [
            ['office' => 'Dallas HQ', 'city' => 'Dallas'],
            ['office' => 'Dallas South', 'city' => 'Dallas'],
            ['office' => 'Austin Branch', 'city' => 'Austin'],
        ];

        $groupedEmployees = collect($employees)->mapToGroups(fn (array $item, int $key) => [$item['city'] => $item['name']]);
        $groupedOffices = collect($offices)->mapToGroups(fn (array $item, int $key) => [$item['city'] => $item['office']]);

        $result = $groupedEmployees->map(function(Collection $employees, string $city) use ($groupedOffices) {
            $currentOffices = $groupedOffices->get($city);
            return $currentOffices->mapWithKeys(fn(string $office, string $city) => [
                $office => $employees
            ]);
        });

        $this->prittyPrint($result);
        $this->uglyPrint($result);
    }

    private function prittyPrint(Collection $result): void
    {
        $result->each(function(Collection $hqWithEmployees, string $city) {
            $hqWithEmployees->each(function(Collection $hqWithEmployees, string $hq) use ($city) {
                $this->info(sprintf('City: %s; HQ: %s; Emplyees: %s', $city, $hq, $hqWithEmployees->implode(', ')));
            });
        });
    }

    private function uglyPrint(Collection $result): void
    {
        dd($result->toArray());
    }
}

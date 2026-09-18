<?php

use App\Models\Employee;
use App\Models\EmployeeLoan;

$employees = Employee::active()->inRandomOrder()->take(20)->get();

foreach ($employees as $emp) {
    // Random loan amount between 1 million and 5 million
    $amount = rand(10, 50) * 100000;

    // Total installments (3, 6, 12 months)
    $months = [3, 6, 12];
    $totalInstallments = $months[array_rand($months)];

    $installment = round($amount / $totalInstallments, 2);

    // Loan date random within last 3 months
    $loanDate = now()->subDays(rand(0, 90));

    EmployeeLoan::create([
        'employee_id' => $emp->id,
        'loan_date' => $loanDate->format('Y-m-d'),
        'amount' => $amount,
        'installment' => $installment,
        'remaining_balance' => $amount,
        'total_installments' => $totalInstallments,
        'paid_installments' => 0,
        'status' => 'active',
        'notes' => 'Pinjaman Simulasi / Koperasi',
    ]);
}

echo "Successfully generated 20 dummy loans.\n";

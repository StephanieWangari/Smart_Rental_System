<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\Tenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements WithMultipleSheets
{
    public function __construct(private string $month) {}

    public function sheets(): array
    {
        return [
            new CompletedPaymentsSheet($this->month),
            new OutstandingTenantsSheet($this->month),
        ];
    }
}

class CompletedPaymentsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private string $month) {}

    public function title(): string { return 'Completed Payments'; }

    public function headings(): array
    {
        return ['#', 'Tenant', 'Email', 'Phone', 'Property', 'Amount (KES)', 'M-Pesa Ref', 'Date'];
    }

    public function collection()
    {
        return Payment::with('tenant.user', 'tenant.property')
            ->where('status', 'completed')
            ->where('month_paid', $this->month)
            ->get()
            ->map(fn($p, $i) => [
                $i + 1,
                $p->tenant->user->name,
                $p->tenant->user->email,
                $p->phone_number,
                $p->tenant->property->name,
                number_format($p->amount, 2),
                $p->mpesa_transaction_id ?? '—',
                $p->updated_at->format('d M Y'),
            ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

class OutstandingTenantsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(private string $month) {}

    public function title(): string { return 'Outstanding Payments'; }

    public function headings(): array
    {
        return ['#', 'Tenant', 'Email', 'Phone', 'Property', 'Rent Due (KES)'];
    }

    public function collection()
    {
        return Tenant::where('status', 'active')
            ->whereDoesntHave('payments', fn($q) =>
                $q->where('month_paid', $this->month)->where('status', 'completed')
            )
            ->with('user', 'property')
            ->get()
            ->map(fn($t, $i) => [
                $i + 1,
                $t->user->name,
                $t->user->email,
                $t->phone,
                $t->property->name,
                number_format($t->property->rent_amount, 2),
            ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Penggajian' => null]" />
    <h1 class="h3 mb-4 text-gray-800">Penggajian Karyawan</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('payrolls.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="period_id" id="period-select" class="form-select" onchange="this.form.submit()">
                        <option value="">Pilih Periode</option>
                        @foreach($periods as $p) <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option> @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(isset($selectedPeriod))
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light"><tr><th>No</th><th>Karyawan</th><th>Gaji Kotor</th><th>Potongan</th><th>Gaji Bersih</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($payrolls as $index => $payroll)
                            <tr>
                                <td>{{ $index + 1 }}</td><td>{{ $payroll->employee->name }}</td>
                                <td>Rp{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                                <td><strong>Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                                <td><a href="{{ route('payrolls.show', $payroll->id) }}" class="btn btn-sm btn-info text-white">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-center">Silakan pilih periode penggajian</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#period-select",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            onChange: function(value) {
                if (value !== '') {
                    document.getElementById('period-select').form.submit();
                }
            }
        });
    });
</script>
@endpush

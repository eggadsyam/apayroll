@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Periode Payroll' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Periode Payroll</h1>
        <a href="{{ route('payroll-periods.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Periode</a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('payroll-periods.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="period_id" id="period-select" class="form-select">
                        <option value="">Semua Periode</option>
                        @foreach($allPeriods as $p)
                            <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="waiting_approval" {{ request('status') == 'waiting_approval' ? 'selected' : '' }}>Waiting Approval</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('payroll-periods.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Periode</th>
                            <th>Bulan/Tahun</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Tgl Pembayaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periods as $index => $period)
                            <tr>
                                <td>{{ $periods->firstItem() + $index }}</td>
                                <td>{{ $period->name }}</td>
                                <td>{{ $period->formatted_period }}</td>
                                <td>{{ $period->start_date ? \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $period->end_date ? \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $period->payment_date ? \Carbon\Carbon::parse($period->payment_date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($period->status == 'draft') <span class="badge bg-secondary">Draft</span>
                                    @elseif($period->status == 'processing') <span class="badge bg-primary">Processing</span>
                                    @elseif($period->status == 'completed') <span class="badge bg-success">Completed</span>
                                    @else <span class="badge bg-info">{{ ucfirst($period->status) }}</span> @endif
                                </td>
                                <td>
                                    <a href="{{ route('payroll-periods.show', $period->id) }}" class="btn btn-sm btn-info text-white" title="Detail/Proses"><i class="fas fa-eye"></i></a>
                                    @if($period->status == 'draft')
                                    <a href="{{ route('payroll-periods.edit', $period->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $period->id }}"><i class="fas fa-trash"></i></button>
                                    <x-modal-confirm id="deleteModal{{ $period->id }}" action="{{ route('payroll-periods.destroy', $period->id) }}" title="Hapus" message="Yakin hapus data ini?" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Belum ada data periode payroll</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $periods->appends(request()->query())->links() }}</div>
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
        if(document.getElementById('period-select')) {
            new TomSelect("#period-select", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }
    });
</script>
@endpush

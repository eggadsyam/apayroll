@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Komponen Gaji' => null]" />
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Komponen Gaji</h1>
        <a href="{{ route('salary-components.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Komponen
        </a>
    </div>

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Tipe Perhitungan</th>
                            <th>Nominal Default</th>
                            <th>Kena Pajak</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($components as $index => $item)
                            <tr>
                                <td>{{ $components->firstItem() + $index }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if($item->type == 'earning')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-danger">Potongan</span>
                                    @endif
                                </td>
                                <td>{{ $item->calculation_type == 'fixed' ? 'Tetap' : 'Persentase' }}</td>
                                <td>Rp{{ number_format($item->default_amount, 0, ',', '.') }}</td>
                                <td>{{ $item->taxable ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                <td>
                                    <a href="{{ route('salary-components.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <x-modal-confirm id="deleteModal{{ $item->id }}" action="{{ route('salary-components.destroy', $item->id) }}" title="Hapus Komponen" message="Yakin hapus {{ $item->name }}?" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $components->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

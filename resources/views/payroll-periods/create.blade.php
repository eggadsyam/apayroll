@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Periode Payroll' => route('payroll-periods.index'), 'Tambah' => null]" />
    
    <h1 class="h3 mb-4 text-gray-800">Tambah Periode Payroll</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('payroll-periods.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Bulan <span class="text-danger">*</span></label>
                        <select name="month" class="form-select" required>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="year" value="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

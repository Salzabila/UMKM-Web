@extends('dashboard.layouts.main')

@section('container')
    <style>
        .card-margin {
            margin-bottom: 1.875rem;
        }

        .card {
            border: 0;
            box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -webkit-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -moz-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -ms-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #ffffff;
            background-clip: border-box;
            border: 1px solid #e6e4e9;
            border-radius: 8px;
        }

        .card .card-header.no-border {
            border: 0;
        }

        .card .card-header {
            background: none;
            padding: 0 0.9375rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            min-height: 50px;
        }

        .card-header:first-child {
            border-radius: calc(8px - 1px) calc(8px - 1px) 0 0;
        }
    </style>
    <div class="container mt-3">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <div class="row">
                <div class="col-3">
                    <div class="card-header">
                        Input Data Transaksi
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="/dashboard/cashier/create">
                    @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">No Nota</p>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="no_nota" required
                                placeholder="Masukkan No Nota..." value="<?php
                                echo uniqid(time(), true);
                                ?>" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Tanggal Transaksi</p>
                        </div>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_transaksi" value="<?php echo date('Y-m-d'); ?>"
                                readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nama Administrator</p>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select" name="user_id">
                                @foreach ($users as $user)
                                    @if (old('user_id') == $user->id)
                                        <option value="{{ $user->id }}" selected>{{ $user->nama }}
                                        </option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->nama }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nama Pembeli</p>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nama_pembeli" required
                                placeholder="Masukkan Nama Pembeli..." value="{{ old('nama_pembeli') }}">
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary" type="submit">Daftarkan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
@endsection

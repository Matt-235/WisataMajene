@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Wisata: {{ $wisata->nama_wisata }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="nama_wisata" class="form-label">Nama Wisata</label>
                                    <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" value="{{ $wisata->nama_wisata }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $wisata->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ $wisata->lokasi }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="Alam" {{ $wisata->kategori == 'Alam' ? 'selected' : '' }}>Alam</option>
                                        <option value="Budaya" {{ $wisata->kategori == 'Budaya' ? 'selected' : '' }}>Budaya</option>
                                        <option value="Sejarah" {{ $wisata->kategori == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                                        <option value="Religi" {{ $wisata->kategori == 'Religi' ? 'selected' : '' }}>Religi</option>
                                        <option value="Kuliner" {{ $wisata->kategori == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="harga_tiket" class="form-label">Harga Tiket</label>
                                    <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" value="{{ $wisata->harga_tiket }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating (1-5)</label>
                                    <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" value="{{ $wisata->rating }}">
                                </div>
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                    @if ($wisata->gambar)
                                        <p class="mt-2">Gambar saat ini: <br>
                                            <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" alt="{{ $wisata->nama_wisata }}" style="max-width: 200px;">
                                        </p>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('wisata.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
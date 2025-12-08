@extends('layouts.verivied')

@section('content')
<div class="profile-edit">
    <h2>Edit Profil</h2>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        @if ($user->isClient())
            <div>
                <label>Tinggi Badan (cm)</label>
                <input type="number" name="tb" value="{{ old('tb', $user->client?->tb) }}" required>
            </div>
            <div>
                <label>Berat Badan (kg)</label>
                <input type="number" name="bb" value="{{ old('bb', $user->client?->bb) }}" required>
            </div>
            <div>
                <label>Jenis Kelamin</label>
                <select name="gender" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ (old('gender', $user->client?->gender) == 'L') ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ (old('gender', $user->client?->gender) == 'P') ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label>Umur</label>
                <input type="number" name="umur" value="{{ old('umur', $user->client?->umur) }}" required>
            </div>

        @elseif ($user->isTrainer())
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $user->trainer?->nama) }}" required>
            </div>
            <div>
                <label>Keahlian</label>
                <textarea name="keahlian" required>{{ old('keahlian', $user->trainer?->keahlian) }}</textarea>
            </div>
            <div>
                <label>Sertifikasi (opsional)</label>
                <textarea name="sertifikasi">{{ old('sertifikasi', $user->trainer?->sertifikasi) }}</textarea>
            </div>

        @endif

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('profile.show') }}">Batal</a>
    </form>

    @if ($errors->any())
        <div class="text-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
@extends('layouts.verivied')

@section('content')
<div class="profile-container">
    <h2>Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($user->isClient())
        <h3>Data Klien</h3>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Tinggi Badan:</strong> {{ $user->client->tb ?? '–' }} cm</p>
        <p><strong>Berat Badan:</strong> {{ $user->client->bb ?? '–' }} kg</p>
        <p><strong>Jenis Kelamin:</strong> {{ ($user->client->gender ?? null) === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
        <p><strong>Umur:</strong> {{ $user->client->umur ?? '–' }} tahun</p>

    @elseif ($user->isTrainer())
        <h3>Data Trainer</h3>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Nama:</strong> {{ $user->trainer->nama ?? '–' }}</p>
        <p><strong>Keahlian:</strong> {{ $user->trainer->keahlian ?? '–' }}</p>
        <p><strong>Sertifikasi:</strong> {{ $user->trainer->sertifikasi ?? '–' }}</p>

    @endif

    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
</div>
@endsection
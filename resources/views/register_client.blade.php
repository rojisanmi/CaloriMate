<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Complete Profile</title>
  <style>
    body { font-family: Arial; background:#f4f6f8; display:flex; align-items:center; justify-content:center; height:100vh; }
    form { background:#fff; padding:2em; width:340px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,.1); }
    input, select, button { width:100%; margin:.5em 0; padding:.7em; border:1px solid #ccc; border-radius:4px; }
    button { background:#2e86de; color:#fff; border:none; cursor:pointer; }
  </style>
</head>
<body>
  <form method="POST" action="{{ route('register.client.store') }}">
    @csrf

    <label>Username (locked)
      <input value="{{ $username }}" readonly>
    </label>

    <label>Tinggi Badan (cm)
      <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required>
    </label>

    <label>Berat Badan (kg)
      <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" required>
    </label>

    <label>Gender
      <select name="gender" required>
        <option value="" disabled selected>Pilih</option>
        <option value="L" {{ old('gender')==='L'?'selected':'' }}>Laki-laki</option>
        <option value="P" {{ old('gender')==='P'?'selected':'' }}>Perempuan</option>
        <!-- or M/F if you prefer -->
      </select>
    </label>

    <label>Umur
      <input type="number" name="umur" min="5" max="120" value="{{ old('umur') }}" required>
    </label>

    @if ($errors->any())
      <ul style="color:red">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <button type="submit">Selesai</button>
  </form>
</body>
</html>

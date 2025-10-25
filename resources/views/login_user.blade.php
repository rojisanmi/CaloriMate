<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>

<body>
  <h2>Login As User</h2>
  @if ($errors->any())
  <div style="color:red">{{ $errors->first() }}</div> @endif
  <form method="POST" action="{{ route('login.do') }}">
    @csrf
    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>

  <p><a href="{{ route('register.form') }}">Register</a></p>
</body>

</html>
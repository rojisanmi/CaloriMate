<h1>Trainer Home</h1>
<p>Welcome, {{ session('user_name') }} (role: {{ session('user_role') }})</p>
<form method="POST" action="{{ route('logout') }}">@csrf<button>Logout</button></form>

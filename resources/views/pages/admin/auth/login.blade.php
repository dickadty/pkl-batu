<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin PPID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-1">Login Admin PPID</h4>
                        <p class="text-center text-muted mb-4">
                            Panel Administrasi Kota Batu
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Username atau password salah.
                            </div>
                        @endif

                        <form action="{{ route('admin.login.process') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                                    autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-danger w-100">
                                Login
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-muted mt-3 small">
                    PPID Kota Batu
                </p>
            </div>
        </div>
    </div>
</body>

</html>

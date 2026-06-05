<!DOCTYPE html>
<html>
<head><title>Connexion...</title></head>
<body>
<script>
    const token = new URLSearchParams(window.location.search).get('token');
    const user  = JSON.parse(decodeURIComponent(new URLSearchParams(window.location.search).get('user')));

    if (token && user) {
        localStorage.setItem('auth_token', token);
        localStorage.setItem('auth_user', JSON.stringify(user));

        const role = user.role;
        if (role === 'admin')  window.location = '/dashboard/admin';
        else if (role === 'owner') window.location = '/dashboard/owner';
        else window.location = '/dashboard/client';
    } else {
        window.location = '/login?error=google_failed';
    }
</script>
<p style="font-family:sans-serif;text-align:center;margin-top:4rem">Connexion en cours...</p>
</body>
</html>
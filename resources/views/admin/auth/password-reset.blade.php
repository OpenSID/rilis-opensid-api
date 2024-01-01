<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h1>Permintaan Reset Sandi</h1>

    <p>Anda menerima pesan ini dikarenakan terdapat permintaan untuk mereset password pada akun anda.</p>
    <p>
        Untuk mereset, silahkan klik link dibawah ini
        <a href="{{ $resetLink }}">{{ $resetLink }}</a>
    </p>
    <p>jika anda tidak merasa melakukan reset password, If you didn't request a password reset, Tidak ada Tindakan yang perlu dilakukan.</p>
</body>
</html>

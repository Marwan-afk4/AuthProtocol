<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f6f6; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333333;">Hello, {{ $name }} 👋</h2>
        <p style="font-size: 16px; color: #555555;">Thank you for registering! To complete your sign-up, please verify your email address by using the following verification code:</p>
        <div style="margin: 30px 0; text-align: center;">
            <span style="display: inline-block; font-size: 30px; font-weight: bold; letter-spacing: 4px; background-color: #f0f0f0; padding: 15px 30px; border-radius: 5px; color: #007bff;">
                {{ $code }}
            </span>
        </div>
        <p style="font-size: 14px; color: #999999;">If you didn’t request this, you can safely ignore this email.</p>
        <p style="font-size: 14px; color: #999999;">— The {{ config('app.name') }} Team</p>
    </div>
</body>
</html>

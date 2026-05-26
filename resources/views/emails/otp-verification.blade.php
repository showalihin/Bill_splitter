<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0f172a; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 460px;">
                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom: 32px;">
                            <div style="display: inline-block; background: linear-gradient(135deg, #f97316, #8b5cf6); border-radius: 12px; padding: 12px; margin-bottom: 12px;">
                                <img src="https://img.icons8.com/fluency/48/star.png" alt="BillSplitter" width="24" height="24" style="display: block;">
                            </div>
                            <div style="font-size: 24px; font-weight: 700; color: #ffffff;">
                                Bill<span style="background: linear-gradient(135deg, #f97316, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Splitter</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td style="background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 40px 32px;">
                            <!-- Greeting -->
                            <div style="font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">
                                Hey {{ $userName }}! 👋
                            </div>
                            <div style="font-size: 15px; color: #94a3b8; margin-bottom: 28px; line-height: 1.6;">
                                Use the verification code below to confirm your email address. This code expires in <strong style="color: #cbd5e1;">10 minutes</strong>.
                            </div>

                            <!-- OTP Code -->
                            <div style="background: rgba(249, 115, 22, 0.08); border: 1px solid rgba(249, 115, 22, 0.2); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 28px;">
                                <div style="font-size: 36px; font-weight: 800; letter-spacing: 12px; color: #f97316; font-family: 'Courier New', monospace;">
                                    {{ $otp }}
                                </div>
                            </div>

                            <!-- Security Note -->
                            <div style="font-size: 13px; color: #64748b; line-height: 1.6; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.06);">
                                🔒 If you didn't request this code, please ignore this email. Never share your verification code with anyone.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top: 24px;">
                            <div style="font-size: 12px; color: #475569;">
                                &copy; {{ date('Y') }} BillSplitter. Split bills, not friendships.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

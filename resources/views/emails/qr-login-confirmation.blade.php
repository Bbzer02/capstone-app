<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Login Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">CTU Danao HRMO</h1>
        <p style="color: #e0e7ff; margin: 5px 0 0 0;">Human Resource Management Office</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb;">
        <h2 style="color: #1e40af; margin-top: 0;">QR Code Login Confirmation</h2>
        
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>This email confirms that you have successfully logged in to the CTU Danao HRMO system using your QR code ID card.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #3b82f6; margin: 20px 0;">
            <p style="margin: 0;"><strong>Login Details:</strong></p>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Name:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Login Time:</strong> {{ $loginTime->format('F d, Y h:i A') }}</li>
                @if($ipAddress)
                <li><strong>IP Address:</strong> {{ $ipAddress }}</li>
                @endif
            </ul>
        </div>
        
        <div style="background: #fef3c7; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 20px 0;">
            <p style="margin: 0; color: #92400e;">
                <strong>⚠️ Security Notice:</strong> If you did not perform this login, please contact the HRMO immediately and change your password.
            </p>
        </div>
        
        <p style="margin-top: 30px;">
            If you have any questions or concerns, please contact the HRMO office.
        </p>
        
        <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
            This is an automated message. Please do not reply to this email.
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6b7280; font-size: 12px;">
        <p>&copy; {{ date('Y') }} CTU Danao HRMO. All rights reserved.</p>
    </div>
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Received</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Received!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $applicant->name }},</h2>
            
            <p>Thank you for your interest in the <strong>{{ $applicant->job->title }}</strong> position at our company.</p>
            
            <p>We have successfully received your application and our HR team will review it carefully. You can expect to hear back from us within 5-7 business days.</p>
            
            <div style="background: white; padding: 20px; border-radius: 6px; margin: 20px 0;">
                <h3>Application Details:</h3>
                <ul>
                    <li><strong>Position:</strong> {{ $applicant->job->title }}</li>
                    <li><strong>Department:</strong> {{ $applicant->job->department }}</li>
                    <li><strong>Application Date:</strong> {{ $applicant->created_at->format('M d, Y') }}</li>
                    <li><strong>Status:</strong> Under Review</li>
                </ul>
            </div>
            
            <p>If you have any questions about your application, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            HR Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interview Scheduled</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 8px 8px; }
        .interview-details { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #10b981; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Interview Scheduled!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $interview->applicant->name }},</h2>
            
            <p>Great news! We were impressed with your application for the <strong>{{ $interview->applicant->job->title }}</strong> position and would like to invite you for an interview.</p>
            
            <div class="interview-details">
                <h3>Interview Details:</h3>
                <ul>
                    <li><strong>Position:</strong> {{ $interview->applicant->job->title }}</li>
                    <li><strong>Date:</strong> {{ $interview->scheduled_at->format('l, F j, Y') }}</li>
                    <li><strong>Time:</strong> {{ $interview->scheduled_at->format('g:i A') }}</li>
                    <li><strong>Duration:</strong> Approximately 45-60 minutes</li>
                </ul>
                
                @if($interview->notes)
                    <p><strong>Additional Notes:</strong><br>{{ $interview->notes }}</p>
                @endif
            </div>
            
            <p>Please arrive 10 minutes early and bring a copy of your resume. If you need to reschedule or have any questions, please contact us as soon as possible.</p>
            
            <p>We look forward to meeting you!</p>
            
            <p>Best regards,<br>
            HR Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

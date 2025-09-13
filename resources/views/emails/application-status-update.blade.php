<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: {{ $status === 'approved' ? '#10b981' : '#ef4444' }}; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 8px 8px; }
        .status-box { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid {{ $status === 'approved' ? '#10b981' : '#ef4444' }}; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status Update</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $applicant->name }},</h2>
            
            @if($status === 'approved')
                <p>Congratulations! We are pleased to inform you that your application for the <strong>{{ $applicant->job->title }}</strong> position has been approved and you have been shortlisted for the next stage of our hiring process.</p>
                
                <div class="status-box">
                    <h3>Next Steps:</h3>
                    <ul>
                        <li>Our HR team will contact you within 2-3 business days to schedule an interview</li>
                        <li>Please prepare for a technical interview and be ready to discuss your experience</li>
                        <li>Make sure to have your references ready</li>
                    </ul>
                </div>
                
                <p>We are excited about the possibility of you joining our team!</p>
            @else
                <p>Thank you for your interest in the <strong>{{ $applicant->job->title }}</strong> position. After careful consideration, we have decided not to move forward with your application at this time.</p>
                
                <div class="status-box">
                    <p>While we were impressed with many aspects of your application, we have chosen to proceed with other candidates whose qualifications more closely match our current needs.</p>
                </div>
                
                <p>We encourage you to apply for other positions that may be a better fit for your skills and experience. We will keep your application on file for future opportunities.</p>
            @endif
            
            <p>Thank you for your time and interest in our company.</p>
            
            <p>Best regards,<br>
            HR Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

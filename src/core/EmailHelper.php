<?php

class EmailHelper {
    
    /**
     * Kirim email menggunakan PHP mail()
     * Note: Memerlukan konfigurasi SMTP di php.ini atau menggunakan sendmail
     */
    public static function sendMail($to, $subject, $message, $fromEmail, $fromName) {
        // Set headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $fromName . " <" . $fromEmail . ">\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email
        return mail($to, $subject, $message, $headers);
    }
    
    /**
     * Format pesan kontak menjadi HTML
     */
    public static function formatContactMessage($name, $email, $subject, $message, $companyName) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a7c22; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #4a7c22; }
                .value { margin-top: 5px; padding: 10px; background: white; border-left: 3px solid #4a7c22; }
                .footer { text-align: center; color: #666; font-size: 12px; padding: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Pesan Baru dari Website</h2>
                </div>
                
                <div class="content">
                    <div class="field">
                        <div class="label">Nama Pengirim:</div>
                        <div class="value">' . htmlspecialchars($name) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Email:</div>
                        <div class="value">' . htmlspecialchars($email) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Subject:</div>
                        <div class="value">' . htmlspecialchars($subject) . '</div>
                    </div>
                    
                    <div class="field">
                        <div class="label">Pesan:</div>
                        <div class="value">' . nl2br(htmlspecialchars($message)) . '</div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>Pesan ini dikirim dari form kontak website ' . htmlspecialchars($companyName) . '</p>
                    <p>Untuk membalas, silakan kirim email langsung ke: ' . htmlspecialchars($email) . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Kirim email konfirmasi ke pengirim
     */
    public static function sendConfirmationEmail($to, $name, $companyName, $companyEmail) {
        $subject = "Terima kasih telah menghubungi kami - " . $companyName;
        
        $message = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4a7c22; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { text-align: center; color: #666; font-size: 12px; padding: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>' . htmlspecialchars($companyName) . '</h2>
                </div>
                
                <div class="content">
                    <p>Halo ' . htmlspecialchars($name) . ',</p>
                    
                    <p>Terima kasih telah menghubungi kami melalui website. Pesan Anda telah kami terima dan akan kami proses sesegera mungkin.</p>
                    
                    <p>Tim kami akan menghubungi Anda dalam 1-2 hari kerja.</p>
                    
                    <p>Jika Anda memiliki pertanyaan mendesak, silakan hubungi kami langsung melalui email: ' . htmlspecialchars($companyEmail) . '</p>
                    
                    <p>Salam,<br>' . htmlspecialchars($companyName) . '</p>
                </div>
                
                <div class="footer">
                    <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
                </div>
            </div>
        </body>
        </html>';
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $companyName . " <" . $companyEmail . ">\r\n";
        
        return mail($to, $subject, $message, $headers);
    }
}
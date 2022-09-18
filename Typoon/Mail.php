<?php

class Mail
{
    public function send($toAdrs, $fromAdrs, $subject, $body)
    {
        if (!filter_var($toAdrs, FILTER_FLAG_EMAIL_UNICODE)) {
            return false;
        } elseif (!filter_var($fromAdrs, FILTER_FLAG_EMAIL_UNICODE)) {
            return false;
        }

        $header = "From: $fromAdrs\nReply-To: $fromAdrs\n";
        return mb_send_mail($toAdrs, $subject, $body, $header);
    }
}

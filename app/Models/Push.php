<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

class Push extends Model
{
    protected $table = 'push';
    protected $primaryKey = 'push_id';

    public function sendMessageToAndroid($registration_id, $message) {
        //Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_id,
            'data' => $message,
        );
        // Update your Google Cloud Messaging API Key
        defined("GOOGLE_API_KEY") or define("GOOGLE_API_KEY", "AIzaSyBcueZ3fvsSZOv3pGVQjOuV1A9Ji4hIDu4");
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function sendMessageToIOS($registration_id, $message) {
        $tokens[] = $registration_id;
// Put your private key's passphrase here:
        $passphrase = 'ktbffh';

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-distr-cert.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client(
        'ssl://gateway.sandbox.push.apple.com:2195', $err,
        $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        //if (!$fp)	exit("Failed to connect: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body


        $body['aps'] = array(
            'alert' => $message['message'],
            'sound' => 'default',
            'id' => $message['id'],
            'kind' => $message['kind'],
            'badge' => 1
        );

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification

        try {
            foreach ($tokens as $token)
            {
                $msg = chr(0) . pack('n', 32) . pack('H*', $token[0]) . pack('n', strlen($payload)) . $payload;
                // Send it to the server
                $result = fwrite($fp, $msg, strlen($msg));
            }
        }catch (Exception $e){

        }


        //if (!$result)	echo 'Message not delivered' . PHP_EOL; else	echo 'Message successfully delivered' . PHP_EOL;

        // Close the connection to the server
        fclose($fp);
    }
}

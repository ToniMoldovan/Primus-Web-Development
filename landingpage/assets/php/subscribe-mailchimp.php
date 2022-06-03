<?php

    #Clears white spaces
    function cleanUserInput($data){
        $cleanData = htmlspecialchars($data);
        $cleanData = stripslashes($data);
        $cleanData = trim($data);

        return $cleanData;
    }

    if (isset($_POST['submit'])) {
        
        $email = cleanUserInput($_POST['email']);

        $list_id = 'd0ca49fb1c';
        $api_key = '3ee5a13751840d31cd6cd8fe9df54c65-us12';
        
        $data_center = substr($api_key,strpos($api_key,'-')+1);
        
        $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
        
        $json = json_encode([
            'email_address' => $email,
            'status'        => 'subscribed', //pass 'subscribed' or 'pending'
        ]);
        
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $result = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        
            if (200 == $status_code) {
                echo "The user added successfully to the MailChimp.";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }

    }


?>
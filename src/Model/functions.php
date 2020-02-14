
<?php
require_once(__DIR__ . '../../../vendor/autoload.php');
curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/../../cacert.pem");

use App\Entity\User;
use Mailgun\Mailgun;
use Messente\Api\Api\OmnimessageApi;
use Messente\Api\Model\Omnimessage;
use Messente\Api\Configuration;
use Messente\Api\Model\SMS;


function generateRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}

function verification($plainPassword, $user_email, $userId)
{
    //Link to verification account
    $link = "http://127.0.0.1:8000/user/verification/" . $userId;

    //EMAIL SENDING
    $mgClient = Mailgun::create(getenv('MAILGUN_KEY'));
    $domain = getenv('MAILGUN_DOMAIN');
    # Make the call to the client.
    $result = $mgClient->messages()->send($domain,  array(
        'from'	=> getenv('MAILGUN_DOMAIN_USER'),
        'to'	=> $user_email, //EMAIL TO SEND, when test yo cannot use different
        'subject' => 'Hello ' . $user_email . ', here you activation link',
        'text'	=>  'Hello ' . $user_email . ' you password is: ' . $plainPassword .
            ', please confirm you account by link: ' . $link,
    ));


}

function sendNotificationEmail(User $user, $user_email, $text)
{

    //EMAIL SENDING
    $mgClient = Mailgun::create(getenv('MAILGUN_KEY'));
    $domain = getenv('MAILGUN_DOMAIN');
    # Make the call to the client.
    $result = $mgClient->messages()->send($domain,  array(
        'from'	=> 'Roadtodev <'.getenv('MAILGUN_DOMAIN_USER').'>',
        'to'	=> $user_email, //EMAIL TO SEND, when test yo cannot use different
        'subject' => 'Hello ' . $user->getName() . ' ',
        'text'	=>  $text
    ));
}

function sendSmsNotification(User $user, $text){

    $config = Configuration::getDefaultConfiguration()
        ->setUsername(getenv('SMS_USER_NAME'))
        ->setPassword(getenv('SMS_USER_PASSWORD'));

    $apiInstance = new OmnimessageApi(
        new GuzzleHttp\Client(),
        $config
    );

    $omnimessage = new Omnimessage([
        'to' => $user->getPhone(),
    ]);

    //SMS
    $sms = new SMS(
        [
            'text' => $text,
            'from' => 'FitnessClub',
        ]
    );

    $omnimessage->setMessages([$sms]);
    try {
        $result = $apiInstance->sendOmnimessage($omnimessage);
        print_r($result);
    } catch (Exception $e) {
        echo 'Exception when calling sendOmnimessage: ', $e->getMessage(), PHP_EOL;
    }
}


?>
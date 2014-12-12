<?php namespace Modbase\Disqus;

use Illuminate\Support\Facades\Config;

/**
 * Basic helper class to be used for SSO authentication with Disqus.
 */
class Disqus {

    /**
     * Secret Disqus API key
     * 
     * @var string
     */
    protected $privateKey;


    /**
     * Public Disqus API key
     * 
     * @var string
     */
    protected $publicKey;


    /**
     * Creates a new Disqus instance                      
     */
    public function __construct()
    {
        $this->privateKey = Config::get('disqus-sso::key.private');
        $this->publicKey = Config::get('disqus-sso::key.public');
    }


    /**
     * The final payload that must be sent to Disqus in order to associate the user.
     * Example usage: this.page.remote_auth_s3 = "<?php echo DisqusAuth::payload(); ?>";
     *
     * @param  string $userData The user data to authenticate with. Only 'id', 'username' and 'email' are used.
     * 
     * @return string
     */
    public function payload($userData)
    {
        // Only grab the id, username and email
        // TODO: check avatar and other!!
        if ( ! is_array($userData) )
        {
            $userData = $userData->toArray();
        }

        $userData = array_only($userData, ['id', 'username', 'email']);

        $timestamp = time();
        $encodedData = $this->getEncodedData($userData);

        return $encodedData . ' ' . $this->getHMAC($encodedData, $timestamp) . ' ' . $timestamp;
    }


    /**
     * The public API key.
     * Example usage: this.page.api_key = "<?php echo DisqusAuth::publicKey(); ?>";
     * 
     * @return string
     */
    public function publicKey()
    {
        return $this->publicKey;
    }


    /**
     * Base64 encoded string of the JSON encoded user data.
     * 
     * @param  string $userData The data to encode
     * @return string
     */
    private function getEncodedData(array $userData)
    {
        return base64_encode(json_encode($userData));
    }


    /**
     * Disqus specific encrypted hash of <encoded user data> <timestamp>.
     * 
     * @param  string $encodedData The encoded user data
     * @param  integer $timestamp Unix timestamp
     * @return string
     */
    protected function getHMAC($encodedData, $timestamp)
    {
        $message = $encodedData . ' ' . $timestamp;
        
        return hash_hmac('sha1', $message, $this->privateKey);
    }
}
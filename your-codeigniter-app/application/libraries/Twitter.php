<?php
require "twitter_lib/vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

        
class Twitter {


       
        protected $CI;
        protected $CONSUMER_KEY;
        protected $CONSUMER_SECRET;
        protected $ACCESS_TOKEN;
        protected $ACCESS_TOKEN_SECRET;

        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();

                $result=$this->CI->db->get_where('twitter_settings',array('iId'=>1))->row();
                $this->CONSUMER_KEY=$result->vConsumerKey;
                $this->CONSUMER_SECRET=$result->vConsumerSecret;
                $this->ACCESS_TOKEN=$result->vConsumerToken;
                $this->ACCESS_TOKEN_SECRET=$result->vConsumerTokenSecret;
        }

        function getConnection()
        {

                $connection = new TwitterOAuth($this->CONSUMER_KEY, $this->CONSUMER_SECRET, $this->ACCESS_TOKEN, $this->ACCESS_TOKEN_SECRET);

                return $connection;

        }


}

?>

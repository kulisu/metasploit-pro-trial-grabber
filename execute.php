<?php
  /**
   * This is a PHP command-line script to auto-grab the Metasploit's 7-DAYs pro trial key.
   * 
   * Really tired to submit the trial request to Metasploit manually to get the pro key every weeks just because of its unfriendly price ?
   * You have a new choice today !
   * Just run this script and wait a minute, it will generate a new pro trial key for you.
   * It's using Fake Name Generator and Fake Mail Generator to fetch the contact information to register, all are fake and disposable.
   * All the register processes are in automation, so enjoy it now !
   * 
   * @author Chris Lin <Chris#skiddie.me>
   * @link https://github.com/skiddie/metasploit-pro-trial-grabber
   * @version 2014-07-18
   */

  # Start: defining the pre-required constants
  echo "[+] defining the pre-required constants .. ";
    # if you got a `SSL certificate problem: unable to get local issuer certificate` error, please set `ENABLE_VERIFY` to FALSE to disable SSL verification.
    define( 'ENABLE_VERIFY', FALSE );
    define( 'REQUEST_DELAY', 15 );
    define( 'REQUEST_RETRY', 30 );
    define( 'PROVIDER_LIST', serialize( array( 'Fakemailgenerator' => FALSE, 'Guerrillamail' => FALSE, 'Spambog' => TRUE, 'Yopmail' => TRUE ) ) );
  echo "DONE !", PHP_EOL;
  # End

  # Start: loading the 3rd-party libraries
  echo "[+] loading the 3rd-party libraries .. ";
    require_once 'vendor/autoload.php';
  echo "DONE !", PHP_EOL;
  # End

  # Start: creating all classes' instance
  echo "[+] creating all classes' instance .. ";
    $rnd = array_rand( array_flip( array_keys( unserialize( PROVIDER_LIST ), TRUE ) ), 1 );

    $eml = new $rnd();
    $fng = new Fakenamegenerator();
    $msf = new Metasploit();
  echo "DONE !", PHP_EOL;
  # End

  # Start: checking all mail domains from provider: $obj are valid or not
  echo "[+] checking all mail domains from provider: $rnd are valid or not ..", PHP_EOL;
    $domains = $eml->get_available_domains();
    $fields  = $fng->get_profile_fields();
    $address = $msf->check_mail_address( $fields['user_name'], $domains );
  echo "[+] ALL DONE !", PHP_EOL;
  # End

  # Start: choosing a valid domain and generating an email address
  echo "[+] choosing a valid domain and generating an email address .. ";
    $total = count( $address['valid'] ) - 1;
    if ( $total > 0 ) {
      $fields['email'] = sprintf( '%s@%s', $fields['user_name'], $address['valid'][rand( 0, $total )]['value'] );
    } else {
      echo PHP_EOL, "[x] no valid domains, exiting ..", PHP_EOL;
      die();
    }
  echo "DONE !", PHP_EOL;
  # End

  # READY TO FIRE !
  echo PHP_EOL, "[+] READY TO FIRE !", PHP_EOL, PHP_EOL;

  # Start: submitting the trial request to metasploit
  echo "[+] submitting the trial request to metasploit ..", PHP_EOL;
    $hidden = $msf->get_hidden_values();
    $msf->submit_trial_request( $fields, $hidden );
  echo "[+] ALL DONE !", PHP_EOL;
  # End

  # Start: looping to retrieve the trial mail content
  echo "[+] looping to retrieve the trial mail content ..", PHP_EOL;
  echo $eml->get_trial_license( $fields['email'], REQUEST_DELAY );
  # End

  # MISSION COMPLETED :-D
  echo PHP_EOL, PHP_EOL, "if you like this script, buy me a coffee ?";
  echo PHP_EOL, "Paypal: Chris#skiddie.me, BitCoin: 1BBJgGhyFMJcwnzCVZz2LxM2htsQBy9nWd", PHP_EOL;

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-22
   */
  abstract class Grabber {
    protected $curl_object;
    protected $grab_result;
    protected $html_object;
    protected $service_url;
    protected $sxml_object;

    protected function __construct() {
      $this->curl_object = new Curl();
      $this->grab_result = NULL;
      $this->html_object = new simple_html_dom();
      $this->service_url = NULL;
      $this->sxml_object = NULL;

      $headers = array(
        'User-Agent' => random_uagent()
      );

      foreach ( $headers as $key => $value ) {
        $this->curl_object->setHeader( $key, $value );
       }

      $this->curl_object->error( function( $instance ) {
       	echo PHP_EOL, "[?] cURL error occurred ..", PHP_EOL;
       	echo '[?] error code: ', $instance->error_code, PHP_EOL;
       	echo '[?] error message: ', $instance->error_message, PHP_EOL;
       	die();
      });
      
      $this->curl_object->setOpt(CURLOPT_SSL_VERIFYPEER, ENABLE_VERIFY);
    }

    protected function __destruct() {
      $this->curl_object->close();
      $this->html_object->clear();
      unset( $this->grab_result, $this->service_url, $this->sxml_object );
    }
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-22
   */
  abstract class Disposable extends Grabber {
    protected function __construct() {
      parent::__construct();
    }

    protected function __destruct() {
      parent::__destruct();
    }

    protected function get_available_domains( $pattern = 'option' ) {
      $domains = array();

      # sending the GET request to retrieve the HTML raw code
      $this->curl_object->get( $this->service_url );
      # ready to parse some fields we're interested
      $this->html_object->load( $this->curl_object->response );

      # Start: parsing all the available domains from response
      echo "  [-] parsing all the available domains from response .. ";
        foreach ( $this->html_object->find( $pattern ) as $domain ) {
          array_push( $domains, array( 'key' => $domain->value, 'value' => str_replace( array( '@', ' (PW)', '&#64;' ), '', $domain->plaintext ) ) );
        }
      echo "DONE !", PHP_EOL;
      # End

      # return the domains value we've parsed
      $this->grab_result = $domains;
      return $this->grab_result;
    }

    abstract protected function get_trial_license( $email, $delay = 45 );
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-29
   */
  class Fakemailgenerator extends Disposable {
    public function __construct() {
      parent::__construct();
      $this->service_url = 'http://www.fakemailgenerator.com';
    }

    public function __destruct() {
      parent::__destruct();
    }

  	/**
  	 * parsing the fakemailgenerator mail content to get all available domains
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.fakemailgenerator.com/
  	 * @return array returns an array of all the available mail domains
  	 * @version 2014-03-22
  	 */
    public function get_available_domains() {
      return parent::get_available_domains();
    }

  	/**
  	 * parsing the fakemailgenerator mail content to get the trial license in looping
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.fakemailgenerator.com/inbox/fleckens.hu/ceshounce1986/
  	 * @param string $email a mail address parsed from fakemailgenerator to receive the trial license
  	 * @param int $delay waiting for %d seconds to get again if the trial info has not delivered
  	 * @return string the metasploit pro trial product key for 7-days
  	 * @version 2014-03-29
  	 */
    public function get_trial_license( $email, $delay = 45 ) {
      $address = explode( '@', $email );
      $inbox   = sprintf( '%s/inbox/%s/%s/', $this->service_url, $address[1], $address[0] );
      $pattern = 'a[id=active-email]';
      $retry   = 0;

      # checking the trial confirmation mail has delivered to inbox or not
      echo "  [-] checking the trial confirmation mail has delivered to inbox or not ..", PHP_EOL;
        do {
          if ( $retry < REQUEST_RETRY ) {
          	# sending the GET request to retrieve the HTML raw code
          	$this->curl_object->get( $inbox );
          	# ready to parse some fields we're interested
          	$this->html_object->load( $this->curl_object->response );
          	
          	# <a href="/inbox/fleckens.hu/carljlange/message-24871826/" id="active-email">
          	#   <span class="user">Swofford@mail.appfield.net, Caitlin &lt;caitlin_swofford@rapid7.com&gt;</span>
          	#   <span class="theme">Rapid7 Metasploit Pro Trial License Activated</span>
          	#   <span class="arrow selected"></span>
          	#   <span class="date">5:18 AM EDT</span>
          	# </a>
          	$content = $this->html_object->find( $pattern, 0 );

          	if ( empty( $content ) ) {
          		$retry++;
          		# no luck, waiting for %d second(s) to step into the new loop to fetch again
          		echo "      [*] waiting for trial mail delivered to inbox: $inbox ..", PHP_EOL;
          		sleep( $delay );
          	} else {
          	  # BINGO !
          	  echo "      [*] BINGO ! the mail just delivered, parsing it .. ";
                # http://www.fakemailgenerator.com/inbox/fleckens.hu/carljlange/message-21872104/
          	    $url = explode( '/', str_replace( '-', '/', $content->href ) );

          	    # http://www.fakemailgenerator.com/email.php?id=21872104
          	    $this->curl_object->get( sprintf( '%s/email.php?id=%s', $this->service_url, $url[5] ) );
          	    $this->html_object->load( $this->curl_object->response );

          	    # parsing the trial serial
          	    preg_match( '/\w{4}-\w{4}-\w{4}-\w{4}/', $this->html_object->find( 'table tr td span', 0 )->parent()->plaintext, $license );
          	  echo "DONE !";
          	}
          } else {
            echo "[x] reaching the maximum retry count, exiting ..";
            return;
          }
        } while ( empty( $license ) );
      echo PHP_EOL, "  [-] ALL DONE !", PHP_EOL;
      echo "[+] ALL DONE !", PHP_EOL, PHP_EOL;
      # End

      # return the 7-DAYS pro serial we want, DONE !
      $this->return_result = sprintf( 'Your 7-days pro trial key: %s', $license[0] );
      return $this->return_result;
    }
  }

  /** 
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-22
   */
  class Fakenamegenerator extends Grabber {
    public function __construct() {
      parent::__construct();
      $this->service_url = 'http://www.fakenamegenerator.com/advanced.php';
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * parsing the fakenamegenerator profile content to get the fake fields likes name, phone, etc.
     * 
     * @author Chris Lin <Chris#skiddie.me>
     * @link http://www.fakenamegenerator.com/advanced.php?t=country&n[]=us&c[]=us&gen=85&age-min=19&age-max=45
     * @return array returns an array of fake profile fields
     * @version 2014-02-23
     */
    public function get_profile_fields() {
      $field   = array();
      $pattern = 'div[class=extra]';
      $payload = array(
        'age-max' => '45',
        'age-min' => '19',
        'c[]'     => 'us',
        'gen'     => '85',
        'n[]'     => 'us',
        't'       => 'country'
      );

      # sending the GET request to retrieve the HTML raw code
      $this->curl_object->get( $this->service_url, $payload );
      # ready to parse some fields we're interested
      $this->html_object->load( $this->curl_object->response );

      # Start: parsing the profile fields from response
      echo "    [*] parsing the profile fields from response .. ";
        $full_name            = explode( ' ', $this->html_object->find( 'div[class=info]', 0 )->children( 0 )->children( 0 )->children( 0 )->plaintext );
        $fields['first_name'] = $full_name[0];
        $fields['last_name']  = $full_name[2];
        $fields['user_name']  = strtolower( $this->html_object->find( $pattern, 0 )->children( 0 )->children( 7 )->plaintext );
      echo "DONE !", PHP_EOL;
      # End

      # Start: parsing the additional info from response
      echo "    [*] parsing the additional info from response .. ";
        $fields['title']        = $this->html_object->find( $pattern, 0 )->children( 0 )->children( 34 )->plaintext;
        $fields['company_name'] = $this->html_object->find( $pattern, 0 )->children( 0 )->children( 37 )->plaintext;
        $fields['phone']        = sprintf( '+1%s', str_replace( '-', '', trim( $this->html_object->find( $pattern, 0 )->children( 0 )->children( 1 )->children( 0 )->plaintext ) ) );
        # $fields['email']      = strtolower( $this->html_resource->find( $pattern, 0 )->children(0)->children(4)->children(0)->plaintext );
        # $fields['address']    = str_replace( '<br/>', ' ', trim( $this->html_resource->find( 'div[class=info]', 0 )->children(0)->children(0)->children(1)->innertext ) );
      echo "DONE !", PHP_EOL;
      # End

      # return the fields value we've parsed
      $this->grab_result = $fields;
      return $this->grab_result;
    }
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-22
   */
  class Guerrillamail extends Disposable {
    public function __construct() {
      parent::__construct();
      $this->service_url = 'https://www.guerrillamail.com';
    }

    public function __destruct() {
      parent::__destruct();
    }

  	/**
  	 * parsing the guerrillamail mail content to get all available domains
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link https://www.guerrillamail.com/
  	 * @return array returns an array of all the available mail domains
  	 * @version 2014-03-22
  	 */
    public function get_available_domains() {
      return parent::get_available_domains();
    }

  	/**
  	 * parsing the guerrillamail mail content to get the trial license in looping
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @param string $email a mail address parsed from guerrillamail to receive the trial license
  	 * @param int $delay waiting for %d seconds to get again if the trial info has not delivered
  	 * @return string the metasploit pro trial product key for 7-days
  	 * @version 2014-03-22
  	 */
    public function get_trial_license( $email, $delay = 45 ) {
      # TODO: ALL MAIL DOMAINS BANNED BY METASPLOIT
      # return parent::get_trial_license( $email, $delay );
    }
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-29
   */
  class Spambog extends Disposable {
    public function __construct() {
      parent::__construct();
      $this->service_url = 'http://www.spambog.com';
    }

    public function __destruct() {
      parent::__destruct();
    }

  	/**
  	 * parsing the spambog mail content to get all available domains
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.spambog.com/
  	 * @return array returns an array of all the available mail domains
  	 * @version 2014-03-22
  	 */
    public function get_available_domains() {
      return parent::get_available_domains();
    }

  	/**
  	 * parsing the spambog mail content to get the trial license in looping
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.spambog.com/rss/upind1981@bund.us
  	 * @param string $email a mail address parsed from spambog to receive the trial license
  	 * @param int $delay waiting for %d seconds to get again if the trial info has not delivered
  	 * @return string the metasploit pro trial product key for 7-days
  	 * @version 2014-03-29
  	 */
    public function get_trial_license( $email, $delay = 45 ) {
      $inbox = sprintf( '%s/rss/%s', $this->service_url, $email );
      $retry = 0;

      # checking the trial confirmation mail has delivered to inbox or not
      echo "  [-] checking the trial confirmation mail has delivered to inbox or not ..", PHP_EOL;
        do {
          if ( $retry < REQUEST_RETRY ) {
            # sending the GET request to retrieve the HTML raw code
            $this->curl_object->get( $inbox );
            # ready to parse some fields we're interested
            $this->sxml_object = new SimpleXMLElement( $this->curl_object->response );

            # <link>http://www.spambog.com/message-48975819-7a3bd25f97097ab7f4edff5ef58e2bc2-1394838000/upind1981@bund.us.htm</link>
            $content = $this->sxml_object->channel->item->link;

            if ( empty( $content ) ) {
          	  $retry++;
          	  # no luck, waiting for %d second(s) to step into the new loop to fetch again
              echo "      [*] waiting for trial mail delivered to inbox: $inbox ..", PHP_EOL;
              sleep( $delay );
            } else {
              # BINGO !
              echo "      [*] BINGO ! the mail just delivered, parsing it .. ";
                # http://www.spambog.com/message-48975819-7a3bd25f97097ab7f4edff5ef58e2bc2-1394838000/upind1981@bund.us.htm
                preg_match( '/\w{8}-\w{32}-\w{10}/', $content, $url );
                # cookies of sid: 6e4mapj8tp7oc80j49vnb64oe7
                preg_match( '/\w{26}/', implode('|', $this->curl_object->response_headers), $cookies );

                $this->curl_object->setCookie( 'sid', $cookies[0] );
                # http://www.spambog.com/getHTMLVersion-48975819-7a3bd25f97097ab7f4edff5ef58e2bc2-1394838000.htm
                $this->curl_object->get( sprintf( '%s/getHTMLVersion-%s.htm', $this->service_url, $url[0] ) );

                $this->html_object->load( $this->curl_object->response );
                # parsing the trial serial
                preg_match( '/\w{4}-\w{4}-\w{4}-\w{4}/', $this->html_object->find( 'table tr td span', 0 )->parent()->plaintext, $license );
              echo "DONE !";
            }
          } else {
          	echo "[x] reaching the maximum retry count, exiting ..";
          	return;
          }
        } while ( empty( $license ) );
      echo PHP_EOL, "  [-] ALL DONE !", PHP_EOL;
      echo "[+] ALL DONE !", PHP_EOL, PHP_EOL;
      # End

      # return the 7-DAYS pro serial we want, DONE !
      $this->return_result = sprintf( 'Your 7-days pro trial key: %s', $license[0] );
      return $this->return_result;
    }
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-29
   */
  class Yopmail extends Disposable {
    public function __construct() {
      parent::__construct();
      $this->service_url = 'http://www.yopmail.com';
    }

    public function __destruct() {
      parent::__destruct();
    }

  	/**
  	 * parsing the yopmail mail content to get all available domains
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.yopmail.com/
  	 * @return array returns an array of all the available mail domains
  	 * @version 2014-03-22
  	 */
    public function get_available_domains() {
      return parent::get_available_domains( 'td.alt div[!class]' );
    }

  	/**
  	 * parsing the yopmail mail content to get the trial license in looping
  	 *
  	 * @author Chris Lin <Chris#skiddie.me>
  	 * @link http://www.yopmail.com/en/rss.php?login=outramer
  	 * @param string $email a mail address parsed from spambog to receive the trial license
  	 * @param int $delay waiting for %d seconds to get again if the trial info has not delivered
  	 * @return string the metasploit pro trial product key for 7-days
  	 * @version 2014-03-29
  	 */
    public function get_trial_license( $email, $delay = 45 ) {
      $address = explode( '@', $email );
      $inbox   = sprintf( '%s/rss.php?login=%s', $this->service_url, $address[0] );
      $retry   = 0;

      # checking the trial confirmation mail has delivered to inbox or not
      echo "  [-] checking the trial confirmation mail has delivered to inbox or not ..", PHP_EOL;
        do {
        	if ( $retry < REQUEST_RETRY ) {
            # sending the GET request to retrieve the HTML raw code
            $this->curl_object->get( $inbox );
            # ready to parse some fields we're interested
            $this->sxml_object = new SimpleXMLElement( $this->curl_object->response );

            # <link>http://yopmail.com?login=outramer&amp;id=e_ZGDjZmR1ZGx0BQDmZQNjZwx1BGV3BN==</link>
            $content = $this->sxml_object->channel->item->link;

            if ( empty( $content ) ) {
          	  $retry++;
          	  # no luck, waiting for %d second(s) to step into the new loop to fetch again
              echo "      [*] waiting for trial mail delivered to inbox: $inbox ..", PHP_EOL;
              sleep( $delay );
            } else {
              # BINGO !
              echo "      [*] BINGO ! the mail just delivered, parsing it .. ";
                # http://yopmail.com?login=outramer&amp;id=e_ZGDjZmR1ZGx0BQDmZQNjZwx1BGV3BN==
                preg_match( '/\w{32}/', $content, $url );

                # http://www.yopmail.com/mail.php?id=me_ZGDjZmR1ZGx0BQDmZQNjZwx1BGV3BN==
                $this->curl_object->get( sprintf( '%s/mail.php?id=m%s==', $this->service_url, $url[0] ) );
                $this->html_object->load( $this->curl_object->response );

                # parsing the trial serial
                preg_match( '/\w{4}-\w{4}-\w{4}-\w{4}/', $this->html_object->find( 'table tr td span', 0 )->parent()->plaintext, $license );
              echo "DONE !";
            }
          } else {
          	echo "[x] reaching the maximum retry count, exiting ..";
          	return;
          }
        } while ( empty( $license ) );
      echo PHP_EOL, "  [-] ALL DONE !", PHP_EOL;
      echo "[+] ALL DONE !", PHP_EOL, PHP_EOL;
      # End

      # return the 7-DAYS pro serial we want, DONE !
      $this->return_result = sprintf( 'Your 7-days pro trial key: %s', $license[0] );
      return $this->return_result;
    }
  }

  /**
   * @author Chris Lin <Chris#skiddie.me>
   * @version 2014-03-22
   */
  class Metasploit extends Grabber {
    public function __construct() {
      parent::__construct();
      $this->service_url = array( 'base_url' => 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl', 'form_url' => 'https://www.rapid7.com/register/metasploit-trial.jsp?product' );
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * checking which the mail domains are valid from metasploit validation
     *
     * @author Chris Lin <Chris#skiddie.me>
     * @link https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=177&deploy=1&compid=663271&h=5c107be29a3fe5ef6392&vd=emdf+eme+ips&ips=167.216.129.23&em=perat8678@teleworm.us
     * @param string $name a user name to prepend to mail domain
     * @param array $domains an array of all the available mail domains to be extracted
     * @return array return an array of the valid and illegal check result
     * @version 2014-02-16
     */
    public function check_mail_address( $name, $domains ) {
      $illegal  = array();
      $pattern  = 'emdf:true';
      $valid    = array();

      # Start: extracting from all the available domains
      echo "      [-] extracting from all the available domains .. ";
        foreach ( $domains as $domain ) {
          $payload = array(
            'compid'  => 663271,
            'deploy'  => 1,
            'em'      => sprintf( '%s@%s', $name, $domain['value'] ),
            'h'       => '5c107be29a3fe5ef6392',
            'ips'     => long2ip( rand( 0, 255 * 255 ) * rand( 0, 255 * 255 ) ),
            'script'  => 177,
            'vd'      => 'emdf eme ips'
          );

          # sending the GET request to retrieve the HTML raw code
          $this->curl_object->get( $this->service_url['base_url'], $payload );

          # Start: checking the mail address is valid or not
          echo PHP_EOL, "        [*] checking the mail address: $payload[em] is valid or not .. ";
            if ( strpos( $this->curl_object->response, $pattern ) === FALSE ) {
              echo "ILLEGAL";
              array_push( $illegal, array( 'key' => $domain['key'], 'value' => $domain['value'] ) );
            } else {
              echo "VALID !";
              array_push( $valid, array( 'key' => $domain['key'], 'value' => $domain['value'] ) );
            }
          # End
        }
      echo PHP_EOL, "      [-] ALL DONE !", PHP_EOL;
      # End

      # return the validate info we've parsed
      $this->grab_result = array( 'valid' => $valid, 'illegal' => $illegal );
      return $this->grab_result;
    }

    /**
     * parsing the hidden filds' value from metasploit registration form
     *
     * @author Chris Lin <Chris#skiddie.me>
     * @link https://www.rapid7.com/register/metasploit-trial.jsp?product
     * @return array return an array of the hidden filds' value
     * @version 2014-02-16
     */
    public function get_hidden_values() {
      #$keys   = array( 'custparamleadsource', 'custparamreturnpath', 'custparamproductaxscode' );
      $values = array();

      # sending the GET request to retrieve the HTML raw code
      $this->curl_object->get( $this->service_url['form_url'] );
      # ready to parse some fields we're interested
      $this->html_object->load( $this->curl_object->response );

      # Start: parsing the hidden filds' value from response
      echo "  [-] parsing the hidden filds' value from response ..";
        foreach ( $this->html_object->find( 'input[type=hidden]') as $hidden ) {
          $key   = $hidden->name;
          $value = $hidden->value;
          $values[$key] = $value;
          echo PHP_EOL, "      [*] field: $key has value: $value";
        }
        # https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=214&deploy=1&compid=663271&h=f545d011e89bdd812fe1
        $values['form_action'] = $this->html_object->find( 'form[id=submitForm]', 0 )->action;
        # echo PHP_EOL, "      [*] form: action has value: $values[form_action]";
      echo PHP_EOL, "  [-] ALL DONE !", PHP_EOL;
      # End

      # return the hidden values we've parsed
      $this->grab_result = $values;
      return $this->grab_result;
    }
    # End

    /**
     * submitting the trial request to the registration form
     *
     * @author Chris Lin <Chris#skiddie.me>
     * @link https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=214&deploy=1&compid=663271&h=f545d011e89bdd812fe1
     * @param array $profile the applicant's contact information
     * @param array $hidden the hidden fields in this form
     * @version 2014-02-23
     */
    public function submit_trial_request( $profile, $hidden = NULL ) {
      echo "  [-] preparing the registration payload .. ";
        $payload = array(
          # maybe there will have a captcha validation in the future ? handle it by yourself !
          # reference: http://www.dama2.com/
          # 'custparamcaptcha'		=> '',
          'custparamfirstname'      => $profile['first_name'],
          'custparamlastname'       => $profile['last_name'],
          'custparamtitle'          => $profile['title'],
          'custparamcompanyname'    => $profile['company_name'],
          'custparamcountry'        => 'TW',
          'custparamstate'          => 0,
          'custparamuse'            => 'Personal',
          'custparamphone'          => $profile['phone'],
          'custparamemail'          => $profile['email'],
          'custparamleadsource'     => ( empty( $hidden ) ) ? 443597 : $hidden['custparamleadsource'],
          'submitted'               => ( empty( $hidden ) ) ? '' : $hidden['submitted'],
          'custparamreturnpath'     => ( empty( $hidden ) ) ? 'https://localhost:3790/setup/activation' : $hidden['custparamreturnpath'],
          'custparamproduct_key'    => ( empty( $hidden ) ) ? '' : $hidden['custparamproduct_key'],
          'custparamproductaxscode' => ( empty( $hidden ) ) ? 'msY5CIoVGr' : $hidden['custparamproductaxscode'],
          'custparamthisIP'         => long2ip( rand( 0, 255 * 255 ) * rand( 0, 255 * 255 ) ),
          'formSubmit'              => 'Get Free Trial'
        );
      echo "DONE !", PHP_EOL;

      # Start: sending the POST request to retrieve the HTML raw code
      echo "  [-] sending the registration data to online form .. ";
        $this->curl_object->post( ( empty( $hidden ) ) ? 'https://forms.netsuite.com/app/site/hosting/scriptlet.nl?script=214&deploy=1&compid=663271&h=f545d011e89bdd812fe1' : $hidden['form_action'], http_build_query( $payload ) );
      echo "DONE !", PHP_EOL;
      # End
    }
  }
?>

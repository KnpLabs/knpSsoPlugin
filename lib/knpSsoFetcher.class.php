<?php

/**
* 
*/
class knpSsoFetcher
{
  
  protected $options;
  
  function __construct(array $options = array())
  {
    $this->options = $options;
  }
  
  public function fetch($ssoKey)
  {
    $request = xmlrpc_encode_request($this->options['method'], array($ssoKey));
    $context = stream_context_create(array(
        'http' => array(
          'method' => "POST",
          'header' => "Content-Type: text/xml",
          'content' => $request
      )));

    try
    {
      $xmlRpcResponse = file_get_contents($this->options['url'], false, $context);
      // Process the XML-RPC request results
      $user = xmlrpc_decode($xmlRpcResponse);
    }
    catch(Exception $e)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        sfContext::getInstance()->getLogger()->warning("{knpSsoFetcher} Exception thrown while fetching the SSO user $e");
      }
      return false;
    }
    
    if(isset($user['faultString']))
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $exception = $user['faultCode'].': '.$user['faultString'];
        sfContext::getInstance()->getLogger()->warning('{knpSsoFetcher} '.$exception);
      }
    }
    return $user;
  }
}

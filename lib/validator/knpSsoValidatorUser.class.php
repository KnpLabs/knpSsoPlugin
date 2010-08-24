<?php

class knpSsoValidatorUser extends sfValidatorBase
{
  public function configure($options = array(), $messages = array())
  {
    $this->addOption('sso_key_field', 'sso_key');
    $this->addOption('throw_global_error', false);

    $this->addRequiredOption('sso_fetcher');

    $this->setMessage('invalid', 'The sso key is invalid.');
    
    parent::configure($options, $messages);
  }

  protected function doClean($values)
  {
    $ssoKey = isset($values[$this->getOption('sso_key_field')]) ? $values[$this->getOption('sso_key_field')] : '';

    // don't allow to sign in with an empty sso key
    if ($ssoKey)
    {
      $ssoFetcher = $this->getOption('sso_fetcher');

      // Fetch the SSO User
      $user = $ssoFetcher->fetch($ssoKey);
      
      // user exists?
      if($user)
      {
        return array_merge($values, array('user' => $user));
      }
    }

    if ($this->getOption('throw_global_error'))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    throw new sfValidatorErrorSchema($this, array($this->getOption('sso_key_field') => new sfValidatorError($this, 'invalid')));
  }

}
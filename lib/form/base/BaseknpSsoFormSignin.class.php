<?php

class BaseknpSsoFormSignin extends BaseForm
{
  /**
   * @see sfForm
   */
  public function setup()
  {
    $ssoFetcher = $this->getOption('sso_fetcher');
    $this->disableCSRFProtection();
    
    $this->setWidgets(array(
      'sso_key' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'sso_key' => new sfValidatorString(),
    ));

    $this->validatorSchema->setPostValidator(new knpSsoValidatorUser(array(
      'sso_fetcher' => $ssoFetcher,
    )));

    $this->widgetSchema->setNameFormat('sso[%s]');
  }
}
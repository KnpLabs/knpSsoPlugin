<?php

class BaseknpSsoAuthActions extends sfActions
{
  protected function getSsoFetcher()
  {
    $class = sfConfig::get('app_knp_sso_plugin_sso_fetcher_class', 'knpSsoFetcher');
    $options = sfConfig::get('app_knp_sso_plugin_sso_fetcher_options', array('method' => '/method', 'url' => 'http://dummy'));
    return new $class($options);
  }
  
  public function executeSignin($request)
  {
    $user = $this->getUser();
    if ($user->isAuthenticated())
    {
      return $this->redirect('@homepage');
    }

    $class = sfConfig::get('app_knp_sso_plugin_signin_form', 'knpSsoFormSignin'); 
    $ssoKeyParameterName = sfConfig::get('app_knp_sso_plugin_sso_key_parameter', 'sso_key'); 
    
    $this->form = new $class(null, array(
      'sso_fetcher' => $this->getSsoFetcher()
    ));

    if ($request->hasParameter($ssoKeyParameterName))
    {
      $this->form->bind(array('sso_key' => $request->getParameter($ssoKeyParameterName)));
      if ($this->form->isValid())
      {
        $values = $this->form->getValues();
        $this->getUser()->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

      }
      else
      {
        return sfView::ERROR;
      }
    }
    else
    {
      // if we have been forwarded, then the referer is the current URL
      // if not, this is the referer of the current request
      $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());

      $module = sfConfig::get('sf_secure_module');
      if ($this->getModuleName() != $module)
      {
        return $this->redirect($module.'/'.sfConfig::get('sf_secure_action'));
      }

      $this->getResponse()->setStatusCode(403);
      $this->setTemplate('secure');
    }
  }

  public function executeSignout($request)
  {
    $this->getUser()->signOut();

    $signoutUrl = sfConfig::get('app_knp_sso_plugin_success_signout_url', $request->getReferer());

    $this->redirect('' != $signoutUrl ? $signoutUrl : '@homepage');
  }

  public function executeSecure($request)
  {
    $this->getResponse()->setStatusCode(403);
  }

}
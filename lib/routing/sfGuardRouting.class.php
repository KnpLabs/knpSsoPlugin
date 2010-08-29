<?php

class knpSsoRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // preprend our routes
    $r->prependRoute('knp_sso_signin', new sfRoute('/sso/login', array('module' => 'knpSsoAuth', 'action' => 'signin'))); 
    $r->prependRoute('knp_sso_signout', new sfRoute('/sso/logout', array('module' => 'knpSsoAuth', 'action' => 'signout'))); 
  }

}
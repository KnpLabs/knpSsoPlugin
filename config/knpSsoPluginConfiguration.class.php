<?php

class knpSsoPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_knp_sso_plugin_routes_register', true))
    {
      $enabledModules = sfConfig::get('sf_enabled_modules', array());
      if (in_array('knpSsoAuth', $enabledModules))
      {
        $this->dispatcher->connect('routing.load_configuration', array('knpSsoRouting', 'listenToRoutingLoadConfigurationEvent'));
      }
    }
  }
}
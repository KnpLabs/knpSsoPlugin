#knpSsoPlugin

The `knpSsoPlugin` is a symfony 1.4 plugin that provides authentication via SSO above the standard security feature of symfony.

It gives you the model (user) and the modules (frontend) to secure your symfony application in a minute in a configurable plugin.
It does not require a database as the user infos are only stored in the user session.

Credits go to the [knpLabs team](http://www.knplabs.com).

## Installation ##

  * Install the plugin (via a git submodule)

        git submodule add git://github.com/knplabs/knpSsoPlugin.git plugins/knpSsoPlugin
  
  * Activate the plugin in the `config/ProjectConfiguration.class.php`
  
        [php]
        class ProjectConfiguration extends sfProjectConfiguration
        {
          public function setup()
          {
            $this->enablePlugins(array(
              'knpSsoPlugin',
              '...'
            ));
          }
        }

## Secure your application ###

To secure a symfony application:

  * Enable the module `knpSsoAuth` in `settings.yml`

        all:
          .settings:
            enabled_modules: [..., knpSsoAuth]

  * Change the default login and secure modules in `settings.yml`

        login_module:           knpSsoAuth
        login_action:           signin
        
        secure_module:          knpSsoAuth
        secure_action:          secure

  * Change the parent class in `myUser.class.php`

        class myUser extends knpSsoSecurityUser
        {
        }

  * Optionally add the following routing rules to `routing.yml`

        knp_sso_signin:
          url:   /login
          param: { module: knpSsoAuth, action: signin }
        
        knp_sso_signout:
          url:   /logout
          param: { module: knpSsoAuth, action: signout }

    You can customize the `url` parameter of each route.

    These routes are automatically registered by the plugin if the module `knpSsoAuth`
    is enabled unless you defined `knp_sso_plugin_routes_register` to false
    in the `app.yml` configuration file:

        all:
          knp_sso_plugin_routes_register:
            routes_register: false

  * Secure some modules or your entire application in `security.yml`

        default:
          is_secure: true

  * Configure your SSO server in your `app.yml`

        all:
          knp_sso_plugin:
            # sso_plugin_signin_form: knpSsoFormSignin
            # sso_key_parameter: sso_key                     # name of the GET parameter you will use
            # sso_fetcher_class: knpSsoFetcher
            sso_fetcher_options:
              method: demo.validateSso
              url: http://www.knplabs.com/xmlrpc.php

          
  * You're done. Now, if you try to access a secure page with a valid sso_key as a parameter, you will have access to the page.

## Customize `knpSsoAuth` module actions ##

If you want to customize or add methods to the knpSsoAuth:

  * Create a `knpSsoAuth` module in your application

  * Create an `actions.class.php` file in your `actions` directory that inherit
    from `BaseknpSsoAuthActions` (don't forget to include the `BaseknpSsoAuthActions`
    as it can't be autoloaded by symfony)

        <?php
    
        require_once(sfConfig::get('sf_plugins_dir').'/knpSsoPlugin/modules/knpSsoAuth/lib/BaseknpSsoAuthActions.class.php');
    
        class knpSsoAuthActions extends BaseknpSsoAuthActions
        {
          public function executeNewAction()
          {
            return $this->renderText('This is a new knpSsoAuth action.');
          }
        }

## `knpSsoSecurityUser` class ##

This class inherits from the `sfBasicSecurityUser` class from symfony and is used for the `user` object in your symfony application (because you changed the `myUser` base class earlier).

So, to access it, you can use the standard `$this->getUser()` in your actions or `$sf_user` in your templates.

`knoSsoSecurityUser` adds some methods:

  * `signIn()` and `signOut()` methods

## Validators ##

`knpSsoPlugin` comes with a validator that you can use in your modules:
`knpSsoValidatorUser`.


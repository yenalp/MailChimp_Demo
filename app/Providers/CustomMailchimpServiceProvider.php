<?php

namespace App\Providers;

use Mailchimp\MailchimpServiceProvider;

class CustomMailchimpServiceProvider extends MailchimpServiceProvider
{
  /**
   * Register paths to be published by the publish command.
   *
   * @return void
   */
  public function boot()
  {
      // Using app to load and rgister config.
  }

  /**
   * Register bindings in the container.
   *
   * @return void
   */
  public function register()
  {
      $this->app->bind('Mailchimp\Mailchimp', function ($app) {
          $config = $app['config']['mailchimp'];

          return new Mailchimp($config['apikey']);
      });
  }
}

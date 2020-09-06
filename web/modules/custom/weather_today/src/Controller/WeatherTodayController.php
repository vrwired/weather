<?php
namespace Drupal\weather_today\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;

/**
 * An example controller.
 */
class WeatherTodayController extends ControllerBase {

  /**
   * Returns a render-able array for weather page.
   *
   * Create a custom module, that displays the weather using any api from the web you can find (no iframes).
   * Create a route, and a page displaying the weather.
   * Also add a library to the module to add css to the page for styling.
   * Also involve editing a twig file to enhance the ux.
   */
  public function content() {
    /** @var \GuzzleHttp\Client $client */
    $client = \Drupal::service('http_client_factory')->fromOptions([
      'base_uri' => 'http://api.weatherstack.com/',
    ]);

    $response = $client->get('current', [
      'query' => [
        'access_key' => 'c461f1744bf41098d04e964db9df4cb5',
        'query' => 'New York'
      ]
    ]);

    $weather = Json::decode($response->getBody());

    $items = [];

    foreach ($weather['current'] as $key => $info) {
      //dump($info);
      if (!is_array($info)) {
        $items[] = $key . ': ' . $info;
      }
      else {
        $image_url = substr($info[0], 0, 4);
        if ($image_url == 'http') {
          $items[] = render($info[0]);
        }
        else {
          $items[] = 'Current conditions: ' . $info[0];
        }
      }
    }

    return [
      '#theme' => 'weather_today',
      '#weather_var' => $items,
    ];
  }

}
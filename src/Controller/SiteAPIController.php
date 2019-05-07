<?php
/**
 * @file
 * Contains \Drupal\site_api\Controller\SiteAPIController.
 */
namespace Drupal\site_api\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class SiteAPIController extends ControllerBase {
    

    public function getdata(Request $request, $id = null) {
        $siteapikey= $this->config('system.site')->get('siteapikey');
        $authorization_header = $request->headers->get('authorization');

        if (($siteapikey !== $authorization_header) || empty($id)) {
            return new JsonResponse(['error' => 'access denied']);
        }

        $node = \Drupal::entityManager()->getStorage('node')->load($id);

        if ($node) {
            $serializer = \Drupal::service('serializer');
            $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
        
            return new Response($data);;
        }
        return new JsonResponse(['error' => 'access denied']);
    }
}
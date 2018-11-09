<?php

namespace ApiBundle\Controller;

use ApiBundle\Exception\BrandException;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BrandController
 * @package ApiBundle\Controller
 */
class BrandController extends Controller
{
    /**
     * @var \ApiBundle\Service\Brand
     */
    private $brandService = null;

    /**
     * @var \ApiBundle\Service\Model
     */
    private $modelService = null;

    /**
     * @var \ApiBundle\Service\Version
     */
    private $versionService = null;

    protected function doSomeStuff()
    {
        parent::doSomeStuff();
        $this->brandService = $this->get('brand_service');
        $this->modelService = $this->get('model_service');
        $this->versionService = $this->get('version_service');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BrandException
     * @throws \ApiBundle\Exception\ModelException
     * @throws \ApiBundle\Exception\VersionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public final function addAction(Request $request)
    {
        $post = $request->request->all();

        if (isset($post['brand']) and strlen($post['brand']) > 1) {
            $brand = $this->brandService->add($post['brand']);

            $model_id = 1;
            while (true) {
                $model = null;
                if (isset($post['model_' . $model_id]) and strlen($post['model_' . $model_id]) > 1) {
                    $model = $this->modelService->add($brand, $post['model_' . $model_id]);

                    if ($model !== null) {
                        $version_id = 1;
                        while (true) {
                            if (isset($post['version_' . $model_id . '_' . $version_id])
                                and strlen($post['version_' . $model_id . '_' . $version_id]) > 1) {
                                $this->versionService->add($model, $post['version_' . $model_id . '_' . $version_id]);
                            } elseif (!isset($post['version_' . $model_id . '_' . $version_id])) {
                                break;
                            }
                            $version_id++;
                        }
                    }
                } elseif (!isset($post['model_' . $model_id])) {
                    break;
                }
                $model_id++;
            }

        } else {
            throw BrandException::nameIsTooShort();
        }

        return new JsonResponse(["added" => true]);

    }
}

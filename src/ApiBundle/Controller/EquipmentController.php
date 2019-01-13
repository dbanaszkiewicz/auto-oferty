<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\Equipment;
use ApiBundle\Utils\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EquipmentController
 * @package ApiBundle\Controller
 */

class EquipmentController extends Controller
{
    /**
     * @var Equipment
     */

    private $equipmentService = null;

    protected function doSomeStuff()
    {
        parent::doSomeStuff();
        $this->equipmentService = $this->get('equipment_service');
    }

    public final function getEquipmentAction(Request $request)
    {
        $equipments = $this->equipmentService->getEquipment();
        return new JsonResponse($equipments);

    }

}

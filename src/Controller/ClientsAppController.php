<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("")
 */
class ClientsAppController extends AbstractController
{
	private $repository;

	private $serializer;

	public function __construct(ClientRepository $repository) 
	{
		$this->repository = $repository;
        $encoders = [
            new JsonEncoder()
        ];

        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);	}

	/**
	 * @Route("/app/clients", name="clients_list")
	 */
	public function index(Request $request)
	{
		$query = $request->get('query');

/* 		$clients = $this->repository->findActiveByName($query, 1);

		$results = array();
		
		foreach ($clients as $key => $client) {
			$name = substr($client->getFullname(), 0, 20);
			$adress = substr($client->getAdress(), 0, 15);
			$city = substr($client->getCity(), 0, 15);

			$results[$key] = array(
				'nom' => $name,
				'adress' => $adress,
				'city' => $client->getPostalCode().' '.$city
			);
		} */

		$client = $this->repository->findActiveOneByName($query);

		$results = array();

		if ($client) {
			$results['name'] = substr($client->getFullname(), 0, 20);
			$results['adress'] = substr($client->getAdress(), 0, 15);
			$results['city'] = $client->getPostalCode().' '.substr($client->getCity(), 0, 15);
		}

		$data = $this->serializer->serialize($results, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
	}
}

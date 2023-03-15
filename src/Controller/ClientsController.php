<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientSearch;
use App\Form\ClientSearchType;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("")
 */
class ClientsController extends AbstractController
{
	private $repository;

	public function __construct(ClientRepository $repository) 
	{
		$this->repository = $repository;
	}

	/**
	 * @Route("/admin/clients", name="clients")
	 */
	public function index(PaginatorInterface $paginator, Request $request)
	{
		$search = new ClientSearch();
		$form = $this->createForm(ClientSearchType::class, $search, array(
			'attr' => ['id' =>'search']
		));
		$form->handleRequest($request);

		if ($request->get('orderBy')) {
			$search->setOrderBy($request->get('orderBy'));
			$search->setOrder($request->get('order'));
		} else {
			$search->setOrderBy('createdAt');
			$search->setOrder('DESC');
		}
		
		$isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());

		$clients = $paginator->paginate(
			$this->repository->findAllQuery($search, $isAdmin),
			$request->query->getInt('page', 1),
			15
		);

		return $this->render('clients/index.html.twig', array(
			'clients' => $clients,
			'form' => $form->createView(),
			'search' => $search
		));
	}

	/**
	 * @Route("/admin/clients/{id}", name="client_show", requirements = {"id"="\d+"})
	 * @ParamConverter("client", class="App\Entity\Client")
	 */
	public function showAction(Client $client)
	{
		return $this->render('clients/show.html.twig', array(
			'client' => $client
		));
	}

	/**
	 * @Route("/admin/clients/add", name="client_add")
	 */
	public function addAction(Request $request)
	{
		$client = new Client;
		$form = $this->createForm(ClientType::class, $client);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$createdAt = new \DateTime();
			$client->setCreatedAt($createdAt);
			$em = $this->getDoctrine()->getManager();
			$em->persist($client);
			$em->flush();

			return $this->redirectToRoute('client_show', array(
				'id' => $client->getId()
			));
		}

		return $this->render('clients/add.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Ajouter'
		));

	}

	/**
	 * @Route("/admin/clients/edit/{id}", name="client_edit", requirements = {"id"="\d+"})
	 * @ParamConverter("client", class="App\Entity\Client")
	 */
	public function editAction(Client $client, Request $request)
	{
		$form = $this->createForm(ClientType::class, $client);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->addFlash('notice', 'Modification éffectuée');

			return $this->redirectToRoute('client_show', array(
				'id' => $client->getId()
			));
		}

		return $this->render('clients/edit.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Modifier'
		));
	}

	/**
	 * @Route("/admin/clients/archive/{id}", name="client_archive", requirements = {"id"="\d+"})
	 * @ParamConverter("client", class="App\Entity\Client")
	 */
	public function archiveAction(Client $client)
	{
		if (!$client) {
			throw $this->createNotFoundException(
				'Pas de client trouvé !'
			);
		}

		if (!$client->getArchivedAt()) {
			$archivedAt = new \DateTime();
			$client->setArchivedAt($archivedAt);
		} else {
			$client->setArchivedAt(null);
		}

		$em = $this->getDoctrine()->getManager();
		$em->flush();

		return $this->redirectToRoute('client_show', array(
			'id' => $client->getId()
		));
	}

	/**
	 * @Route("/admin/clients/delete/{id}", name="client_delete", requirements = {"id"="\d+"})
	 * @ParamConverter("client", class="App\Entity\Client")
	 */
	public function deleteAction(Client $client)
	{
		if (!$client) {
			throw $this->createNotFoundException(
				'Pas de client correspondant !'
			);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($client);
		$em->flush();

		$this->addFlash('notice', 'Client ' . $client->getFullname() . ' supprimé');

		return $this->redirectToRoute('clients');
	}
}

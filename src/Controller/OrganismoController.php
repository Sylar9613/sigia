<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Organismo;
use App\Form\OrganismoType;
use App\Repository\OrganismoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/organismo")
 */
class OrganismoController extends AbstractController
{
    /**
     * @Route("/", name="organismo_index", methods={"GET"})
     */
    public function index(OrganismoRepository $organismoRepository, Request $request): Response
    {
       return $this->render('organismo/index.html.twig', [
            'organismos' => $organismoRepository->findAll(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Organismo'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="organismo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $organismo = new Organismo();
        $form = $this->createForm(OrganismoType::class, $organismo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar organismo');
            $entityManager->persist($bitacora);
            $entityManager->persist($organismo);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Organismo registrado!'
            );

            return $this->redirectToRoute('organismo_index');
        }

        return $this->render('organismo/new.html.twig', [
            'organismo' => $organismo,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Organismo', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="organismo_show", methods={"GET"})
     */
    public function show(Organismo $organismo, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('organismo/show.html.twig', [
            'organismo' => $organismo,
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Organismo', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="organismo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Organismo $organismo): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(OrganismoType::class, $organismo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar organismo: '.$organismo->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Organismo actualizado!'
            );

            return $this->redirectToRoute('organismo_index');
        }

        return $this->render('organismo/edit.html.twig', [
            'organismo' => $organismo,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Organismo', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="organismo_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Organismo $organismo): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $organismo->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar organismo: '.$organismo->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Organismo activado!'
        );
        return $this->redirectToRoute('organismo_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="organismo_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Organismo $organismo)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $osde = $organismo->getOsdes();

        if (count($osde) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este organismo porque posee alguna O.S.D.E.!'
            );

            return true;
        }
        else
        {
            $organismo->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar organismo: '.$organismo->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Organismo desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="organismo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Organismo $organismo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organismo->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $organismo))
            {
                return $this->redirectToRoute('organismo_show', array('id'=>$organismo->getId()));
            }
        }

        return $this->redirectToRoute('organismo_index');
    }
}

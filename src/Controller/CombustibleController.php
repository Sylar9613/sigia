<?php

namespace App\Controller;

use App\Entity\Combustible;
use App\Entity\Log;
use App\Form\CombustibleType;
use App\Repository\CombustibleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/combustible")
 */
class CombustibleController extends AbstractController
{
    /**
     * @Route("/", name="combustible_index", methods={"GET"})
     */
    public function index(CombustibleRepository $combustibleRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('combustible/index.html.twig', [
            'combustibles' => $combustibleRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Combustible'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="combustible_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $combustible = new Combustible();
        $form = $this->createForm(CombustibleType::class, $combustible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar situación del combustible');
            $entityManager->persist($bitacora);
            $entityManager->persist($combustible);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Situación del combustible registrada!'
            );
            return $this->redirectToRoute('combustible_index');
        }

        return $this->render('combustible/new.html.twig', [
            'combustible' => $combustible,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Combustible', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="combustible_show", methods={"GET"})
     */
    public function show(Combustible $combustible, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('combustible/show.html.twig', [
            'combustible' => $combustible,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Combustible', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="combustible_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Combustible $combustible): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(CombustibleType::class, $combustible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar situación del combustible: '.$combustible->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Situación del combustible actualizada!'
            );

            return $this->redirectToRoute('combustible_index');
        }

        return $this->render('combustible/edit.html.twig', [
            'combustible' => $combustible,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Combustible', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="combustible_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Combustible $combustible): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $combustible->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar situación del combustible: '.$combustible->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Situación del combustible activada!'
        );
        return $this->redirectToRoute('combustible_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="combustible_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Combustible $combustible)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        //Arreglar con la Accion de control
        $accion = $combustible->getEvaluacion();

        if (count($accion) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta situación del combustible porque está asociada a alguna Acción de control!'
            );

            return true;
        }
        else
        {
            $combustible->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar situación del combustible: '.$combustible->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Situación del combustible desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="combustible_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Combustible $combustible): Response
    {
        if ($this->isCsrfTokenValid('delete'.$combustible->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $combustible))
            {
                return $this->redirectToRoute('combustible_show', array('id'=>$combustible->getId()));
            }
        }

        return $this->redirectToRoute('combustible_index');
    }
}

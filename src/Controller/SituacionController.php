<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Situacion;
use App\Form\SituacionType;
use App\Repository\SituacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/situacion")
 */
class SituacionController extends AbstractController
{
    /**
     * @Route("/", name="situacion_index", methods={"GET"})
     */
    public function index(SituacionRepository $situacionRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('situacion/index.html.twig', [
            'situacions' => $situacionRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Situación'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="situacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $situacion = new Situacion();
        $form = $this->createForm(SituacionType::class, $situacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar situación del PHD');
            $entityManager->persist($bitacora);
            $entityManager->persist($situacion);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Situación del PHD registrada!'
            );

            return $this->redirectToRoute('situacion_index');
        }

        return $this->render('situacion/new.html.twig', [
            'situacion' => $situacion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Situación', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="situacion_show", methods={"GET"})
     */
    public function show(Situacion $situacion, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('situacion/show.html.twig', [
            'situacion' => $situacion,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Situación', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="situacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Situacion $situacion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(SituacionType::class, $situacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar situación del PHD: '.$situacion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Situación del PHD actualizada!'
            );

            return $this->redirectToRoute('situacion_index');
        }

        return $this->render('situacion/edit.html.twig', [
            'situacion' => $situacion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Situación', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="situacion_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Situacion $situacion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $situacion->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar situación del PHD: '.$situacion->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Situación del PHD activada!'
        );
        return $this->redirectToRoute('situacion_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="situacion_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Situacion $situacion)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $phd = $situacion->getPhds();

        if (count($phd) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta situación porque está asociada a algún PHD!'
            );

            return true;
        }
        else
        {
            $situacion->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar situación del PHD: '.$situacion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Situación del PHD desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="situacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Situacion $situacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$situacion->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $situacion))
            {
                return $this->redirectToRoute('situacion_show', array('id'=>$situacion->getId()));
            }
        }

        return $this->redirectToRoute('situacion_index');
    }
}

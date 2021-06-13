<?php

namespace App\Controller;

use App\Entity\CausaCondicion;
use App\Entity\Log;
use App\Form\CausaCondicionType;
use App\Repository\CausaCondicionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/causas")
 */
class CausaCondicionController extends AbstractController
{
    /**
     * @Route("/", name="causa_condicion_index", methods={"GET"})
     */
    public function index(CausaCondicionRepository $causaCondicionRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('causa_condicion/index.html.twig', [
            'causa_condicions' => $causaCondicionRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Causas'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="causa_condicion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $causaCondicion = new CausaCondicion();
        $form = $this->createForm(CausaCondicionType::class, $causaCondicion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar causa');
            $entityManager->persist($bitacora);
            $entityManager->persist($causaCondicion);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Causa registrada!'
            );
            return $this->redirectToRoute('causa_condicion_index');
        }

        return $this->render('causa_condicion/new.html.twig', [
            'causa_condicion' => $causaCondicion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Causas', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="causa_condicion_show", methods={"GET"})
     */
    public function show(CausaCondicion $causaCondicion, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('causa_condicion/show.html.twig', [
            'causa_condicion' => $causaCondicion,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Causas', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="causa_condicion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CausaCondicion $causaCondicion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(CausaCondicionType::class, $causaCondicion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar causa: '.$causaCondicion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Causa actualizada!'
            );
            return $this->redirectToRoute('causa_condicion_index');
        }

        return $this->render('causa_condicion/edit.html.twig', [
            'causa_condicion' => $causaCondicion,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Causas', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="causa_condicion_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, CausaCondicion $causaCondicion): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $causaCondicion->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar causa: '.$causaCondicion->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Causa activada!'
        );
        return $this->redirectToRoute('causa_condicion_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="causa_condicion_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, CausaCondicion $causaCondicion)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $phd = $causaCondicion->getPhds();

        if (count($phd) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta causa porque está asociada a algún PHD!'
            );

            return true;
        }
        else
        {
            $causaCondicion->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar causa: '.$causaCondicion->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Causa desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="causa_condicion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CausaCondicion $causaCondicion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$causaCondicion->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $causaCondicion))
            {
                return $this->redirectToRoute('causa_condicion_show', array('id'=>$causaCondicion->getId()));
            }
        }

        return $this->redirectToRoute('causa_condicion_index');
    }
}

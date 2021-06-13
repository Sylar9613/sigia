<?php

namespace App\Controller;

use App\Entity\Implicado;
use App\Entity\Log;
use App\Form\ImplicadoType;
use App\Repository\ImplicadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/implicado")
 */
class ImplicadoController extends AbstractController
{
    /**
     * @Route("/", name="implicado_index", methods={"GET"})
     */
    public function index(ImplicadoRepository $implicadoRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('implicado/index.html.twig', [
            'implicados' => $implicadoRepository->findAll(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Implicados'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="implicado_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $implicado = new Implicado();
        $form = $this->createForm(ImplicadoType::class, $implicado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar implicado');
            $entityManager->persist($bitacora);
            $entityManager->persist($implicado);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Implicado registrado!'
            );

            return $this->redirectToRoute('implicado_index');
        }

        return $this->render('implicado/new.html.twig', [
            'implicado' => $implicado,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Implicados', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="implicado_show", methods={"GET"})
     */
    public function show(Implicado $implicado, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('implicado/show.html.twig', [
            'implicado' => $implicado,
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Implicados', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="implicado_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Implicado $implicado): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(ImplicadoType::class, $implicado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar implicado: '.$implicado->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Implicado actualizado!'
            );

            return $this->redirectToRoute('implicado_index');
        }

        return $this->render('implicado/edit.html.twig', [
            'implicado' => $implicado,
            'form' => $form->createView(),
            'url' => 'planificacion',
            'routes' => ['Planificación', 'Parámetros', 'Implicados', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="implicado_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Implicado $implicado): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $implicado->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar el implicado: '.$implicado->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Implicado activado!'
        );
        return $this->redirectToRoute('implicado_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="implicado_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Implicado $implicado)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $implicado->setActivo(false);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Desactivar implicado: '.$implicado->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Implicado desactivado!'
        );

        return false;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="implicado_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Implicado $implicado): Response
    {
        if ($this->isCsrfTokenValid('delete'.$implicado->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $implicado))
            {
                return $this->redirectToRoute('implicado_show', array('id'=>$implicado->getId()));
            }
        }

        return $this->redirectToRoute('implicado_index');
    }
}

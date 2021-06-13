<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Municipio;
use App\Form\MunicipioType;
use App\Repository\MunicipioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/municipio")
 */
class MunicipioController extends AbstractController
{
    /**
     * @Route("/", name="municipio_index", methods={"GET"})
     */
    public function index(MunicipioRepository $municipioRepository, Request $request): Response
    {
        return $this->render('municipio/index.html.twig', [
            'municipios' => $municipioRepository->findAll(),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Municipio'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="municipio_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $municipio = new Municipio();
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar municipio');
            $entityManager->persist($bitacora);
            $entityManager->persist($municipio);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Municipio registrado!'
            );

            return $this->redirectToRoute('municipio_index');
        }

        return $this->render('municipio/new.html.twig', [
            'municipio' => $municipio,
            'form' => $form->createView(),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Municipio', 'Nuevo'],
        ]);
    }

    /**
     * @Route("/{id}", name="municipio_show", methods={"GET"})
     */
    public function show(Municipio $municipio, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('municipio/show.html.twig', [
            'municipio' => $municipio,
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Municipio', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="municipio_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Municipio $municipio): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(MunicipioType::class, $municipio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar municipio: '.$municipio->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Municipio actualizado!'
            );

            return $this->redirectToRoute('municipio_index');
        }

        return $this->render('municipio/edit.html.twig', [
            'municipio' => $municipio,
            'form' => $form->createView(),
            'url' => 'sistema',
            'routes' => ['Ubicación', 'Municipio', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="municipio_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Municipio $municipio): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $municipio->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar municipio: '.$municipio->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Municipio activado!'
        );
        return $this->redirectToRoute('municipio_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="municipio_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Municipio $municipio)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $localidades = $municipio->getLocalidades();

        if (count($localidades) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar este municipio porque posee alguna localidad!'
            );

            return true;
        }
        else
        {
            $municipio->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar municipio: '.$municipio->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Municipio desactivado!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="municipio_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Municipio $municipio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$municipio->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $municipio))
            {
                return $this->redirectToRoute('municipio_show', array('id'=>$municipio->getId()));
            }
        }

        return $this->redirectToRoute('municipio_index');
    }
}

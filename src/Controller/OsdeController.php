<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Osde;
use App\Form\OsdeType;
use App\Repository\OrganismoRepository;
use App\Repository\OsdeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/osde")
 */
class OsdeController extends AbstractController
{
    /**
     * @Route("/", name="osde_index", methods={"GET"})
     */
    public function index(OsdeRepository $osdeRepository, OrganismoRepository $organismoRepository, Request $request): Response
    {
        return $this->render('osde/index.html.twig', [
            'osdes' => $osdeRepository->findAll(),
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Osde'],
        ]);
    }

    /**
     * Filter all osdes by organismos
     *
     * @Route("/", name="osde_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, OsdeRepository $osdeRepository, OrganismoRepository $organismoRepository)
    {
        $idorg = $request->request->get('filtrar');
        if($idorg=='todos')
        {
            return $this->redirectToRoute("osde_index");
        }
        else
        {
            $em = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Filtrar Osde');
            $em->persist($bitacora);
            $em->flush();

            return $this->render("osde/index.html.twig", array(
                'osdes' => $osdeRepository->findBy(array('organismo' => $idorg)),
                'organismos' => $organismoRepository->findBy(array('activo'=>1)),
                'url' => 'clasificador',
                'routes' => ['Empleos', 'Osde'],
            ));
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="osde_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $osde = new Osde();
        $form = $this->createForm(OsdeType::class, $osde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar Osde');
            $entityManager->persist($bitacora);
            $entityManager->persist($osde);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Osde registrada!'
            );

            return $this->redirectToRoute('osde_index');
        }

        return $this->render('osde/new.html.twig', [
            'osde' => $osde,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Osde', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="osde_show", methods={"GET"})
     */
    public function show(Osde $osde, Request $request): Response
    {
        return $this->render('osde/show.html.twig', [
            'osde' => $osde,
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Osde', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="osde_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Osde $osde): Response
    {
        $form = $this->createForm(OsdeType::class, $osde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar Osde: '.$osde->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Osde actualizada!'
            );

            return $this->redirectToRoute('osde_index');
        }

        return $this->render('osde/edit.html.twig', [
            'osde' => $osde,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Osde', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="osde_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Osde $osde): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $osde->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar osde: '.$osde->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'O.S.D.E. activada!'
        );
        return $this->redirectToRoute('osde_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="osde_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Osde $osde)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $entidad = $osde->getEntidades();

        if (count($entidad) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta O.S.D.E. porque posee alguna entidad!'
            );

            return true;
        }
        else
        {
            $osde->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar O.S.D.E.: '.$osde->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'O.S.D.E. desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="osde_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Osde $osde): Response
    {
        if ($this->isCsrfTokenValid('delete'.$osde->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $osde))
            {
                return $this->redirectToRoute('osde_show', array('id'=>$osde->getId()));
            }
        }

        return $this->redirectToRoute('osde_index');
    }
}

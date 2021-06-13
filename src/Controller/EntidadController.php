<?php

namespace App\Controller;

use App\Entity\Entidad;
use App\Entity\Log;
use App\Form\EntidadType;
use App\Repository\EntidadRepository;
use App\Repository\OrganismoRepository;
use App\Repository\OsdeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/entidad")
 */
class EntidadController extends AbstractController
{
    /**
     * @Route("/", name="entidad_index", methods={"GET"})
     */
    public function index(EntidadRepository $entidadRepository, OsdeRepository $osdeRepository, OrganismoRepository $organismoRepository, Request $request): Response
    {
        return $this->render('entidad/index.html.twig', [
            'entidads' => $entidadRepository->findAll(),
            'osdes' => $osdeRepository->findBy(array('activo'=>1)),
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Entidad'],
        ]);
    }

    /**
     * Filter all entidades by ai and/or osdes and/or organismos
     *
     * @Route("/", name="ent_filter", methods={"GET", "POST"})
     */
    public function filterAction(Request $request, EntidadRepository $entidadRepository, OsdeRepository $osdeRepository, OrganismoRepository $organismoRepository)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $idorg = $request->request->get('filtrar_org');
        $idosde = $request->request->get('filtrar_osde');
        $ai = $request->request->get('filtrar_ai');

        $em = $this->getDoctrine()->getManager();

        if($idorg=='todos')
        {
            if ($idosde=='todas')
            {
                if ($ai=='todos')
                {
                    return $this->redirectToRoute("entidad_index");
                }
                elseif ($ai=='yes')
                {
                    $entidads = $entidadRepository->findBy(array('ai'=>'1'));
                }
                else
                {
                    $entidads = $entidadRepository->findBy(array('ai'=>'0'));
                }
            }
            else
            {
                if ($ai=='todos')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>$idosde));
                }
                elseif ($ai=='yes')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>$idosde, 'ai'=>'1'));
                }
                else
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>$idosde, 'ai'=>'0'));
                }
            }
        }
        else
        {
            if ($idosde=='todas')
            {
                if ($ai=='todos')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg)));
                }
                elseif ($ai=='yes')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg), 'ai'=>'1'));
                }
                else
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg), 'ai'=>'0'));
                }
            }
            else
            {
                if ($ai=='todos')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg), 'osde'=>$idosde));
                }
                elseif ($ai=='yes')
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg), 'osde'=>$idosde, 'ai'=>'1'));
                }
                else
                {
                    $entidads = $entidadRepository->findBy(array('osde'=>array('organismo'=>$idorg), 'osde'=>$idosde, 'ai'=>'0'));
                }
            }
        }

        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Filtrar entidad');
        $em->persist($bitacora);
        $em->flush();

        return $this->render("entidad/index.html.twig", array(
            'entidads' => $entidads,
            'osdes' => $osdeRepository->findBy(array('activo'=>1)),
            'organismos' => $organismoRepository->findBy(array('activo'=>1)),
            'url'=>'clasificador',
            'routes' => ['Empleos', 'Entidad'],
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/new", name="entidad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $entidad = new Entidad();
        $form = $this->createForm(EntidadType::class, $entidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Insertar entidad');
            $entityManager->persist($bitacora);
            $entityManager->persist($entidad);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Entidad registrada!'
            );

            return $this->redirectToRoute('entidad_index');
        }

        return $this->render('entidad/new.html.twig', [
            'entidad' => $entidad,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Entidad', 'Nueva'],
        ]);
    }

    /**
     * @Route("/{id}", name="entidad_show", methods={"GET"})
     */
    public function show(Entidad $entidad, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        return $this->render('entidad/show.html.twig', [
            'entidad' => $entidad,
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Entidad', 'Ver'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="entidad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entidad $entidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $form = $this->createForm(EntidadType::class, $entidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Modificar entidad: '.$entidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Entidad actualizada!'
            );

            return $this->redirectToRoute('entidad_index');
        }

        return $this->render('entidad/edit.html.twig', [
            'entidad' => $entidad,
            'form' => $form->createView(),
            'url' => 'clasificador',
            'routes' => ['Empleos', 'Entidad', 'Editar'],
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/activate", name="entidad_activate", methods={"GET","POST"})
     */
    public function activate(Request $request, Entidad $entidad): Response
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());
        $entidad->setActivo(true);
        $bitacora = new Log();
        $bitacora->setFecha(new \DateTime('now'))
            ->setUsuario($this->getUser())
            ->setIp($request->getClientIp())
            ->setAccion('Activar entidad: '.$entidad->getId());
        $this->getDoctrine()->getManager()->persist($bitacora);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Entidad activada!'
        );
        return $this->redirectToRoute('entidad_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/deactivate", name="entidad_deactivate", methods={"GET","POST"})
     */
    public function deactivate(Request $request, Entidad $entidad)
    {
        $session = $request->getSession();
        $session->set('path', $request->getPathInfo());

        $auditor = $entidad->getAuditores();
        $plazas = $entidad->getPlazas();

        if (count($auditor) > 0 || count($plazas) > 0)
        {
            $this->addFlash(
                'error',
                'No se puede eliminar esta entidad porque existe algún auditor en su plantilla!'
            );

            return true;
        }
        else
        {
            $entidad->setActivo(false);
            $bitacora = new Log();
            $bitacora->setFecha(new \DateTime('now'))
                ->setUsuario($this->getUser())
                ->setIp($request->getClientIp())
                ->setAccion('Desactivar entidad: '.$entidad->getId());
            $this->getDoctrine()->getManager()->persist($bitacora);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Entidad desactivada!'
            );

            return false;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}", name="entidad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entidad $entidad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entidad->getId(), $request->request->get('_token'))) {
            if ($this->deactivate($request, $entidad))
            {
                return $this->redirectToRoute('entidad_show', array('id'=>$entidad->getId()));
            }
        }

        return $this->redirectToRoute('entidad_index');
    }
}

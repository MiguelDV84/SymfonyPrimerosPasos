<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use App\Service\TareaManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends AbstractController
{
    #[Route(
        '/',
        name: 'app_listado_tarea'
    )]
    public function listado(TareaRepository $tareaRepository)
    {

        $tareas = $tareaRepository->findAll();
        return $this->render('tarea/listado.html.twig', [
            'tareas' => $tareas,
        ]);
    }

    #[Route(
        '/tarea/crear',
        name: 'app_crear_tarea'
    )]
    public function crear(TareaManager $tareaManager, Request $request): Response
    {
        $tarea = new Tarea();
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            $tarea->setDescripcion($descripcion);
            $errores = $tareaManager->validar($tarea);
            if (empty($errores)) {
                $tareaManager->crear($tarea);
                $this->addFlash('success', 'Tarea creada correctamente');
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                foreach ($errores as $tipo => $error) {
                    $this->addFlash('warning', $error);
                }
                return $this->redirectToRoute('app_listado_tarea');
            }

            
        }
        return $this->render('tarea/crear.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route(
        '/tarea/editar/{id}',
        name: 'app_editar_tarea'
    )]
    public function editar(int $id, Request $request, TareaRepository $tareaRepository, EntityManagerInterface  $em): Response
    {
        $tarea = $tareaRepository->findOneById($id);
        if (null === $tarea) {
            throw $this->createNotFoundException();
        }
        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $tarea->setDescripcion($descripcion);
                $em->flush();
                $this->addFlash('success', 'La tarea ha sido editada correctamente');
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash('warning', 'El campo descripción es obligatorio, la tarea no ha podido realizarse.');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route(
        '/tarea/eliminar/{id}',
        name: 'app_eliminar_tarea'
    )]
    public function eliminar(Tarea $tarea, EntityManagerInterface  $em): Response
    {
        $em->remove($tarea);
        $em->flush();
        $this->addFlash('success', 'Tarea eliminada correctamente.');

        return $this->redirectToRoute('app_listado_tarea');
    }

    #[Route(
        '/tarea/editar-params/{id}',
        name: 'app_editar_tarea_params_convert'
    )]
    public function editarConParamsConvert(Tarea $tarea, Request $request, TareaRepository $tareaRepository, EntityManagerInterface  $em): Response
    {

        $descripcion = $request->request->get('descripcion', null);
        if (null !== $descripcion) {
            if (!empty($descripcion)) {
                $tarea->setDescripcion($descripcion);
                $em->flush();
                $this->addFlash('success', 'La tarea ha sido editada correctamente');
                return $this->redirectToRoute('app_listado_tarea');
            } else {
                $this->addFlash('warning', 'El campo descripción es obligatorio, la tarea no ha podido realizarse.');
            }
        }
        return $this->render('tarea/editar.html.twig', [
            'tarea' => $tarea,
        ]);
    }
}

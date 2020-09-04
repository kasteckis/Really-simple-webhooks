<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Job;
use App\Entity\User;
use App\Form\JobType;
use App\Manager\JobsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    private JobsManager $jobsManager;

    public function __construct(JobsManager $jobsManager)
    {
        $this->jobsManager = $jobsManager;
    }

    /**
     * @Route("/jobs", name="jobs")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index()
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();

        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs
        ]);
    }

    /**
     * @Route("/jobs/add", name="jobs_add")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(JobType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobsManager->saveJob($form, $this->getUser());
            $this->addFlash('success', 'Job was added!');
            return $this->redirectToRoute('jobs');
        }

        return $this->render('jobs/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/jobs/edit/{job}", name="jobs_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param Job $job
     * @return Response
     */
    public function edit(Request $request, Job $job)
    {
        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->jobsManager->saveJob($form, $this->getUser());
            $this->addFlash('success', 'Job was modified!');
            return $this->redirectToRoute('jobs');
        }

        return $this->render('jobs/add_edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/jobs/delete/{job}", name="jobs_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param Job $job
     * @return Response
     */
    public function delete(Request $request, Job $job)
    {
        $this->getDoctrine()->getManager()->remove($job);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Job was deleted!');

        return $this->redirectToRoute('jobs');
    }

    /**
     * @Route("/jobs/trigger/{endpoint}", name="jobs_trigger")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param string $endpoint
     * @return Response
     */
    public function trigger(Request $request, string $endpoint)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $job = $entityManager->getRepository(Job::class)->findOneBy([
            'endpoint' => $endpoint
        ]);

        if ($job instanceof Job) {
            if (!$job->getEnabled()) {
                return $this->json([
                    'response' => 'Job is not enabled'
                ]);
            }

            shell_exec($job->getPathToExecutable());

            $job->setTriggeredDateTime(new \DateTime());

            $build = new Build();
            $build->setDateTime(new \DateTime());
            $build->setJob($job);

            $entityManager->persist($build);
            $entityManager->flush();

            return $this->json([
                'response' => 'Job has been completed'
            ]);
        }

        return $this->json([
            'response' => 'Job not found'
        ]);
    }

    /**
     * @Route("/jobs/builds/{job}", name="jobs_builds")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param Job $job
     * @return Response
     */
    public function builds(Request $request, Job $job)
    {
        $builds = $this->getDoctrine()->getRepository(Build::class)->findBy([
            'job' => $job
        ], [
           'id' => 'DESC'
        ], 100);

        return $this->render('jobs/builds.html.twig', [
            'builds' => $builds,
            'job' => $job
        ]);
    }
}

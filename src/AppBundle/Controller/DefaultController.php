<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Listing;
use AppBundle\Entity\Document;
use AppBundle\Form\Type\ListingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $locale = $request->getLocale();
        $request->setLocale($request);

        $em = $this->get('doctrine.orm.entity_manager');

        $data = $em->getRepository('AppBundle:Listing')->findBy([], ['created' => 'DESC']);

        $view = $this->view($data, 200)
            ->setTemplate("AppBundle:Default:homepage.html.twig")
            ->setTemplateVar('listings')
        ;

        return $this->handleView($view);
    }

    /**
     * @Route("/create", name="createListing")
     */
    public function createAction(Request $request)
    {
        $locale = $request->getLocale();
        $request->setLocale($request);

        $em = $this->get('doctrine.orm.entity_manager');
        $listing = new Listing();
        $document = new Document();
        $listing->setDocument($document);

        $form = $this->createForm(
            new ListingType(),
            $listing,
            array(
                'action' => $this->generateUrl('createListing'),
                //'attr' => [
                //    'class' => 'form'
                // ]
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $file = $form->get('document')->get('file')->getData();
            var_dump($document);

            if (null !== $file) {
                var_dump($file);
                $extension = $file->guessExtension();
                if (!$extension) {
                    $extension = 'bin';
                }
                $newName = rand(1, 99999) . '.' . $extension;
                $file->move($document->getUploadRootDir(), $newName);
                $document->setFile(null);
                $document->setPath($newName);
                $document->setName($newName);
            }

            $em->persist($listing);
            $em->flush();
            $this->addFlash('success' , $this->get('translator')->trans('Listing created!'));
            return $this->redirectToRoute('homepage');

        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error' , $error->getMessage());
            }
        }

        return $this->render(
            'AppBundle:Default:create_listing.html.twig',
            array('form' => $form->createView())
        );

    }

    /**
     * @Route("/remove/{id}", name="removeListing", methods="POST")
     */
    public function removeAction(Listing $id, Request $request)
    {
        $locale = $request->getLocale();
        $request->setLocale($request);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($id);
        $em->flush();

        $this->addFlash('success' , $this->get('translator')->trans('Listing deleted!'));
        return $this->redirectToRoute('homepage');
    }

}

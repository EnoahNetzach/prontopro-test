<?php

namespace AppBundle\Controller;

use AppBundle\Model\BaseFormController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Profile;
use AppBundle\Entity\ProfileExtended;
use AppBundle\Form\ProfileType;

/**
 * @Route("")
 */
class ProfileController extends BaseFormController
{
    /**
     * @Route("/new", name="profile_new")
     * @Route("/edit/{id}", name="profile_edit", requirements={"id" = "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id = null)
    {
        $em = $this->get('doctrine')->getManager();

        $profile = null;

        if (is_null($id)) {
            $profile = new Profile();

            $profileExtended = new ProfileExtended();

            $profile->setProfileExtended($profileExtended);
        } else {
            $profile = $em->getRepository('AppBundle:Profile')->findProfileByIdJoined($id);
        }
 
        $form = $this->createForm(new ProfileType, $profile, array())
            ->add('save', 'submit', array('label' => $this->get('translator')->trans('app.buttons.save')))
        ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($profile);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', $this->get('translator')->trans('app.flash.profile.saved'));

            return new RedirectResponse($this->generateUrl('profile_show', array('id' => $profile->getId())));
        } elseif ($this->formHasErrors($form)) {
            $this->get('session')->getFlashBag()->add('danger', $this->get('translator')->trans('app.form.errors'));
        }
        
        return array(
            'profile' => $profile,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}", name="profile_show", requirements={"id" = "\d+"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->get('doctrine')->getManager();

        $profile = $em->getRepository('AppBundle:Profile')->findProfileByIdJoined($id);

        return array(
            'profile' => $profile,
        );
    }

    /**
     * @Route("/", name="profile_index")
     * @Route("/data.csv", name="profile_data_csv", defaults={"_format": "csv"}, requirements={"_format": "csv"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $profiles = $em->getRepository('AppBundle:Profile')->findAllProfilesJoined();

        $data = array();

        foreach ($profiles as $profile) {
            $description = $profile->getDescription();
            $descriptionExtended = $profile->getProfileExtended()->getDescriptionExtended();
            // remove all the symbols from the descriptions
            $description = preg_replace('/[^\p{L}\p{N}\s]/u', '', $description);
            $descriptionExtended = preg_replace('/[^\p{L}\p{N}\s]/u', '', $descriptionExtended);

            // an array of unique words in the profile description
            $descriptionArray = array_flip(explode(' ', $description));

            // an array of unique words in the extended profile description
            $extendedArray = array_flip(explode(' ', $descriptionExtended));

            $matches = array();

            foreach ($extendedArray as $string => $_) {
                if (isset($matches[$string])) { // it has already been matched
                    continue;
                }

                if (isset($descriptionArray[$string])) {
                    $matches[$string] = true;
                }
            }

            $data[$profile->getId()] = array(
                'id' => $profile->getId(),
                'id_extended' => $profile->getProfileExtended()->getId(),
                'profile' => $profile,
                'count' => count($matches),
            );
        }

        if ($request->getRequestFormat() == 'csv') {
            $response = $this->container->get('templating')->renderResponse('AppBundle:Profile:data.csv.twig', array(
                'data' => $data
            ));

            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'data.csv'
            ));

            return $response; 
        }

        return array(
            'data' => $data,
        );
    }
}

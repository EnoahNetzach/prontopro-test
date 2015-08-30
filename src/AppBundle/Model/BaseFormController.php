<?php

namespace AppBundle\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseFormController extends Controller
{
    static public function getAllFormErrors($form, &$errors = array())
    {
        foreach ($form->getErrors() as $error) {
            $errors[] = $form->getName().': '.$error->getMessage();
        }
        foreach ($form->all() as $child) {
            self::getAllFormErrors($child, $errors);
        }
        return $errors;
    }

    static public function formHasErrors($form)
    {
        if (count($form->getErrors()) > 0) return true;

        foreach ($form->all() as $child) {
            if (self::formHasErrors($child)) return true;
        }
        return false;
    }
}
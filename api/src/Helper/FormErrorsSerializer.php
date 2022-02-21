<?php

namespace App\Helper;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class FormErrorsSerializer {

    public function getFormErrors(Form $form): array {
        return $this->recursiveFormErrors($form->getErrors(true, false), [$form->getName()]);
    }

    private function recursiveFormErrors(FormErrorIterator $formErrors, array $prefixes): array {
        $errors = [];
        foreach ($formErrors as $formError) {
            if ($formError instanceof FormErrorIterator) {
                $errors = array_merge($errors, $this->recursiveFormErrors($formError, array_merge($prefixes, [$formError->getForm()->getName()])));
            } elseif ($formError instanceof FormError) {
                $errors[] = $formError->getMessage();
            }
        }

        return $errors;
    }

}

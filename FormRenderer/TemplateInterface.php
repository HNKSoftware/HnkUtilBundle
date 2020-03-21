<?php

namespace Hnk\HnkUtilBundle\FormRenderer;


use Symfony\Component\Form\FormInterface;

interface TemplateInterface
{
    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */
    public function renderFormHeader(FormInterface $form, string $variableName): string;

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */
    public function renderFormBottom(FormInterface $form, string $variableName): string;

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */
    public function renderFormSubmit(FormInterface $form, string $variableName): string;

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @param string $childName
     * @param string $label
     * @return string
     */
    public function renderFormRow(FormInterface $form, string $variableName, string $childName, string $label): string;

}
<?php

namespace Hnk\HnkUtilBundle\FormRenderer;


use Symfony\Component\Form\FormInterface;

class FormRender
{
    public static function renderFormTemplate(
        FormInterface $form,
        TemplateInterface $template = null,
        $variableName = 'form'
    ): void {
        $renderer = new FormRender();

        echo '<pre>';
        echo htmlspecialchars($renderer->getFormTemplate($form, $template ?: new BaseTemplate(), $variableName));
        echo '</pre>';
        exit;
    }

    public function getFormTemplate(FormInterface $form, TemplateInterface $template, $variableName = 'form'): string
    {
        $html = '';

        $html .= $template->renderFormHeader($form, $variableName);
        $html .= $this->renderFormRow($form, $template, $variableName);
        $html .= $template->renderFormSubmit($form, $variableName);
        $html .= $template->renderFormBottom($form, $variableName);

        return $html;
    }

    protected function renderFormRow(FormInterface $form, TemplateInterface $template, $variableName): string
    {
        $html = '';

        /** @var FormInterface $child */
        foreach ($form->getIterator() as $child) {
            $childName = sprintf("%s.%s", $variableName, $child->getName());

            if ($this->isExpanded($child)) {
                $html .= $this->renderFormRow($child, $template, $childName);
            } else {
                $html .= $template->renderFormRow($form, $variableName, $childName, ucfirst($child->getName()));
            }
        }

        return $html;
    }

    protected function isExpanded(FormInterface $form): string
    {
        if (0 === $form->count()) {
            return false;
        }

        $innerType = $form->getConfig()->getType()->getInnerType();

        if (0 === strpos(get_class($innerType), 'Symfony')) {
            return false;
        }

        return true;
    }
}
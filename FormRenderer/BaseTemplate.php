<?php


namespace Hnk\HnkUtilBundle\FormRenderer;


use Symfony\Component\Form\FormInterface;

class BaseTemplate implements TemplateInterface
{
    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */

    public function renderFormHeader(FormInterface $form, $variableName): string {
        $template = <<<EOT
    <div>
        {{form_start(%s)}}

EOT;
        return sprintf($template, $variableName);
    }

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */
    public function renderFormBottom(FormInterface $form, $variableName): string
    {
        $template = <<<EOT
        {{form_end(%s)}}
    </div>
EOT;

        return sprintf($template, $variableName);
    }

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @return string
     */
    public function renderFormSubmit(FormInterface $form, $variableName): string
    {
        $template = <<<EOT
        <div>
            <input type="submit" value="Save" />
        </div>

EOT;

        return $template;
    }

    /**
     * @param FormInterface $form
     * @param string $variableName
     * @param string $childName
     * @param string $label
     * @return string
     */
    public function renderFormRow(FormInterface $form, $variableName, $childName, $label): string
    {
        $template = <<<EOT
        <div>
            <label>%1\$s</label>
            <div>
                {{form_widget(%2\$s)}}
                {%% if %2\$s.vars.valid == false %%}<span>{{form_errors(%2\$s)}}</span>{%% endif %%}
            </div>
        </div>

EOT;

        return sprintf($template, $label, $childName);
    }
}
<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* core/modules/views_ui/templates/views-ui-build-group-filter-form.html.twig */
class __TwigTemplate_6f314eec5a7cf3c060c97469db347301 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 26
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "form_description", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
        echo "
";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "expose_button", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
        echo "
";
        // line 28
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "group_button", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
        echo "
<div class=\"views-left-40\">
  ";
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "optional", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
        echo "
  ";
        // line 31
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "remember", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
        echo "
</div>
<div class=\"views-right-60\">
  ";
        // line 34
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "widget", [], "any", false, false, true, 34), 34, $this->source), "html", null, true);
        echo "
  ";
        // line 35
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "label", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
        echo "
  ";
        // line 36
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "description", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
        echo "
</div>
";
        // line 42
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["form"] ?? null), 42, $this->source), "form_description", "expose_button", "group_button", "optional", "remember", "widget", "label", "description", "add_group", "more"), "html", null, true);
        // line 54
        echo "
";
        // line 55
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["table"] ?? null), 55, $this->source), "html", null, true);
        echo "
";
        // line 56
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "add_group", [], "any", false, false, true, 56), 56, $this->source), "html", null, true);
        echo "
";
        // line 57
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "more", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "core/modules/views_ui/templates/views-ui-build-group-filter-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 57,  84 => 56,  80 => 55,  77 => 54,  75 => 42,  70 => 36,  66 => 35,  62 => 34,  56 => 31,  52 => 30,  47 => 28,  43 => 27,  39 => 26,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Default theme implementation for Views UI build group filter form.
 *
 * Available variables:
 * - form: A render element representing the form. Contains the following:
 *   - form_description: The exposed filter's description.
 *   - expose_button: The button to toggle the expose filter form.
 *   - group_button: Toggle options between single and grouped filters.
 *   - label: A filter label input field.
 *   - description: A filter description field.
 *   - value: The filters available values.
 *   - optional: A checkbox to require this filter or not.
 *   - remember: A checkbox to remember selected filter value(s) (per user).
 *   - widget: Radio Buttons to select the filter widget.
 *   - add_group: A button to add another row to the table.
 *   - more: A details element for additional field exposed filter fields.
 * - table: A rendered table element of the group filter form.
 *
 * @see template_preprocess_views_ui_build_group_filter_form()
 *
 * @ingroup themeable
 */
#}
{{ form.form_description }}
{{ form.expose_button }}
{{ form.group_button }}
<div class=\"views-left-40\">
  {{ form.optional }}
  {{ form.remember }}
</div>
<div class=\"views-right-60\">
  {{ form.widget }}
  {{ form.label }}
  {{ form.description }}
</div>
{#
  Render the rest of the form elements excluding elements that are rendered
  elsewhere.
#}
{{ form|without(
    'form_description',
    'expose_button',
    'group_button',
    'optional',
    'remember',
    'widget',
    'label',
    'description',
    'add_group',
    'more'
  )
}}
{{ table }}
{{ form.add_group }}
{{ form.more }}
", "core/modules/views_ui/templates/views-ui-build-group-filter-form.html.twig", "/var/www/html/web/core/modules/views_ui/templates/views-ui-build-group-filter-form.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 26, "without" => 42);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 'without'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}

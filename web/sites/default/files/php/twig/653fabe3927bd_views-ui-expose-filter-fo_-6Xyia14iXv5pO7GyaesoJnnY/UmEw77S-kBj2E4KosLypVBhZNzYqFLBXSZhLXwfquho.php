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

/* core/modules/views_ui/templates/views-ui-expose-filter-form.html.twig */
class __TwigTemplate_77c653bb703eb746a21838af71f9d23c extends Template
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
        // line 22
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "form_description", [], "any", false, false, true, 22), 22, $this->source), "html", null, true);
        echo "
";
        // line 23
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "expose_button", [], "any", false, false, true, 23), 23, $this->source), "html", null, true);
        echo "
";
        // line 24
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "group_button", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
        echo "
";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "required", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
        echo "
";
        // line 26
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "label", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
        echo "
";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "description", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
        echo "

";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "operator", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
        echo "
";
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "value", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
        echo "

";
        // line 32
        if (twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "use_operator", [], "any", false, false, true, 32)) {
            // line 33
            echo "  <div class=\"views-left-40\">
  ";
            // line 34
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "use_operator", [], "any", false, false, true, 34), 34, $this->source), "html", null, true);
            echo "
  </div>
";
        }
        // line 37
        echo "
";
        // line 42
        $context["remaining_form"] = $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["form"] ?? null), 42, $this->source), "form_description", "expose_button", "group_button", "required", "label", "description", "operator", "value", "use_operator", "more");
        // line 55
        echo "
";
        // line 59
        if ((($__internal_compile_0 = twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "operator", [], "any", false, false, true, 59)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["#type"] ?? null) : null)) {
            // line 60
            echo "  <div class=\"views-right-60\">
  ";
            // line 61
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["remaining_form"] ?? null), 61, $this->source), "html", null, true);
            echo "
  </div>
";
        } else {
            // line 64
            echo "  ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["remaining_form"] ?? null), 64, $this->source), "html", null, true);
            echo "
";
        }
        // line 66
        echo "
";
        // line 67
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["form"] ?? null), "more", [], "any", false, false, true, 67), 67, $this->source), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "core/modules/views_ui/templates/views-ui-expose-filter-form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  112 => 67,  109 => 66,  103 => 64,  97 => 61,  94 => 60,  92 => 59,  89 => 55,  87 => 42,  84 => 37,  78 => 34,  75 => 33,  73 => 32,  68 => 30,  64 => 29,  59 => 27,  55 => 26,  51 => 25,  47 => 24,  43 => 23,  39 => 22,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Default theme implementation for exposed filter form.
 *
 * Available variables:
 * - form_description: The exposed filter's description.
 * - expose_button: The button to toggle the expose filter form.
 * - group_button: Toggle options between single and grouped filters.
 * - required: A checkbox to require this filter or not.
 * - label: A filter label input field.
 * - description: A filter description field.
 * - operator: The operators for how the filters value should be treated.
 *   - #type: The operator type.
 * - value: The filters available values.
 * - use_operator: Checkbox to allow the user to expose the operator.
 * - more: A details element for additional field exposed filter fields.
 *
 * @ingroup themeable
 */
#}
{{ form.form_description }}
{{ form.expose_button }}
{{ form.group_button }}
{{ form.required }}
{{ form.label }}
{{ form.description }}

{{ form.operator }}
{{ form.value }}

{% if form.use_operator %}
  <div class=\"views-left-40\">
  {{ form.use_operator }}
  </div>
{% endif %}

{#
  Collect a list of elements printed to exclude when printing the
  remaining elements.
#}
{% set remaining_form = form|without(
  'form_description',
  'expose_button',
  'group_button',
  'required',
  'label',
  'description',
  'operator',
  'value',
  'use_operator',
  'more'
  )
%}

{#
  Only output the right column markup if there's a left column to begin with.
#}
{% if form.operator['#type'] %}
  <div class=\"views-right-60\">
  {{ remaining_form }}
  </div>
{% else %}
  {{ remaining_form }}
{% endif %}

{{ form.more }}
", "core/modules/views_ui/templates/views-ui-expose-filter-form.html.twig", "/var/www/html/web/core/modules/views_ui/templates/views-ui-expose-filter-form.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 32, "set" => 42);
        static $filters = array("escape" => 22, "without" => 42);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set'],
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
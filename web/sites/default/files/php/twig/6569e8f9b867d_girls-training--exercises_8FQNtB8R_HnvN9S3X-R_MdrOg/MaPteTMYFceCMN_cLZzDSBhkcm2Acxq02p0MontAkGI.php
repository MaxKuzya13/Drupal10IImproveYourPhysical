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

/* themes/custom/kenny/templates/theme/girls-training--exercises.html.twig */
class __TwigTemplate_b09ef7794db5826403fd75f339f01a79 extends Template
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
        // line 12
        echo "
";
        // line 13
        $context["classes"] = [0 => "taxonomy-term", 1 => ("taxonomy-term--" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 15
($context["vocabulary_machine_name"] ?? null), 15, $this->source))), 2 => ("taxonomy-term--" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source,         // line 16
($context["term"] ?? null), "vid", [], "any", false, false, true, 16), 16, $this->source)))];
        // line 18
        echo "
<div";
        // line 19
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 19), 19, $this->source), "html", null, true);
        echo ">
  <h2>";
        // line 20
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["term"] ?? null), 20, $this->source), "html", null, true);
        echo "</h2>
  <div class=\"exercises-list\">
    ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["content"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 23
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["item"], 23, $this->source), "html", null, true);
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "  </div>
</div>";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/theme/girls-training--exercises.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 25,  62 => 23,  58 => 22,  53 => 20,  49 => 19,  46 => 18,  44 => 16,  43 => 15,  42 => 13,  39 => 12,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override for taxonomy terms with the vocabulary \"girls_training\" and view mode \"exercises\".
 *
 * Available variables:
 * - term: The term entity.
 * - content: An array of term content render elements.
 * - attributes: HTML attributes for the container.
 */
#}

{% set classes = [
  'taxonomy-term',
  'taxonomy-term--' ~ vocabulary_machine_name|clean_class,
  'taxonomy-term--' ~ term.vid|clean_class,
] %}

<div{{ attributes.addClass(classes) }}>
  <h2>{{ term }}</h2>
  <div class=\"exercises-list\">
    {% for item in content %}
      {{ item }}
    {% endfor %}
  </div>
</div>", "themes/custom/kenny/templates/theme/girls-training--exercises.html.twig", "/var/www/html/web/themes/custom/kenny/templates/theme/girls-training--exercises.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 13, "for" => 22);
        static $filters = array("clean_class" => 15, "escape" => 19);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'for'],
                ['clean_class', 'escape'],
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

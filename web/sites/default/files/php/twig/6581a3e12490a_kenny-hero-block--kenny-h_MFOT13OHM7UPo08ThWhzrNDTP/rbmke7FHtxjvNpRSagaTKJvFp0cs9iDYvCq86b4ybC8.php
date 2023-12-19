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

/* themes/custom/kenny/templates/theme/kenny-hero-block--kenny-hero-block-training-body-part.html.twig */
class __TwigTemplate_cebbe10798bca1b069a17b79061759f6 extends Template
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
        // line 1
        $context["bem_block"] = "kenny-hero-block-training-body-part";
        // line 2
        $context["classes"] = [0 =>         // line 3
($context["bem_block"] ?? null), 1 => ((        // line 4
($context["subtitle"] ?? null)) ? (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 4, $this->source) . "--subtitle")) : (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 4, $this->source) . "--no-subtitle")))];
        // line 6
        echo "

<div ";
        // line 8
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 8), 8, $this->source), "html", null, true);
        echo ">
  <img src=\"";
        // line 9
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::imageStyleFilter($this->sandbox->ensureToStringAllowed(($context["image"] ?? null), 9, $this->source), "kenny_hero_block_large"), "html", null, true);
        echo "\" alt=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 9, $this->source), "html", null, true);
        echo "\" class=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 9, $this->source), "html", null, true);
        echo "__image\">

  <div class=\"";
        // line 11
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 11, $this->source), "html", null, true);
        echo "__content\">
    ";
        // line 12
        if (($context["title"] ?? null)) {
            // line 13
            echo "      <h1 class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 13, $this->source), "html", null, true);
            echo "__title\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 13, $this->source), "html", null, true);
            echo "</h1>
    ";
        }
        // line 15
        echo "
    ";
        // line 16
        if (($context["subtitle"] ?? null)) {
            // line 17
            echo "      <div class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 17, $this->source), "html", null, true);
            echo "__subtitle\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["subtitle"] ?? null), 17, $this->source));
            echo "</div>
    ";
        }
        // line 19
        echo "  </div>
</div>


";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/theme/kenny-hero-block--kenny-hero-block-training-body-part.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 19,  81 => 17,  79 => 16,  76 => 15,  68 => 13,  66 => 12,  62 => 11,  53 => 9,  49 => 8,  45 => 6,  43 => 4,  42 => 3,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% set bem_block = 'kenny-hero-block-training-body-part' %}
{% set classes = [
  bem_block,
  subtitle ? bem_block ~ '--subtitle' : bem_block ~ '--no-subtitle',
] %}


<div {{ attributes.addClass(classes) }}>
  <img src=\"{{ image|image_style('kenny_hero_block_large') }}\" alt=\"{{ title }}\" class=\"{{ bem_block }}__image\">

  <div class=\"{{ bem_block }}__content\">
    {% if title %}
      <h1 class=\"{{ bem_block }}__title\">{{ title }}</h1>
    {% endif %}

    {% if subtitle %}
      <div class=\"{{ bem_block }}__subtitle\">{{ subtitle|raw }}</div>
    {% endif %}
  </div>
</div>


", "themes/custom/kenny/templates/theme/kenny-hero-block--kenny-hero-block-training-body-part.html.twig", "/var/www/html/web/themes/custom/kenny/templates/theme/kenny-hero-block--kenny-hero-block-training-body-part.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1, "if" => 12);
        static $filters = array("escape" => 8, "image_style" => 9, "raw" => 17);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape', 'image_style', 'raw'],
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

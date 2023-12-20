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

/* themes/custom/kenny/templates/block/block--system-branding-block.html.twig */
class __TwigTemplate_0c7af0f9fc88fec65752a8547cb3173d extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "block.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("block.html.twig", "themes/custom/kenny/templates/block/block--system-branding-block.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  ";
        if (($context["site_logo"] ?? null)) {
            // line 5
            echo "    <a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getPath("<front>"));
            echo "\" rel=\"home\" class=\"site-logo\">
      <img src=\"";
            // line 6
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_logo"] ?? null), 6, $this->source), "html", null, true);
            echo "\" alt=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Home"));
            echo "\" />
    </a>
  ";
        }
        // line 9
        echo "  ";
        if (($context["site_name"] ?? null)) {
            // line 10
            echo "    <h1 class=\"site-name\">
      <a href=\"";
            // line 11
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getPath("<front>"));
            echo "\" rel=\"home\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_name"] ?? null), 11, $this->source), "html", null, true);
            echo "</a>
    </h1>
  ";
        }
        // line 14
        echo "  ";
        if (($context["site_slogan"] ?? null)) {
            // line 15
            echo "    <div class=\"site-slogan\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_slogan"] ?? null), 15, $this->source), "html", null, true);
            echo "</div>
  ";
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/block/block--system-branding-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 15,  82 => 14,  74 => 11,  71 => 10,  68 => 9,  60 => 6,  55 => 5,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"block.html.twig\" %}

{% block content %}
  {% if site_logo %}
    <a href=\"{{ path('<front>') }}\" rel=\"home\" class=\"site-logo\">
      <img src=\"{{ site_logo }}\" alt=\"{{ 'Home'|t }}\" />
    </a>
  {% endif %}
  {% if site_name %}
    <h1 class=\"site-name\">
      <a href=\"{{ path('<front>') }}\" rel=\"home\">{{ site_name }}</a>
    </h1>
  {% endif %}
  {% if site_slogan %}
    <div class=\"site-slogan\">{{ site_slogan }}</div>
  {% endif %}
{% endblock %}
", "themes/custom/kenny/templates/block/block--system-branding-block.html.twig", "/var/www/html/web/themes/custom/kenny/templates/block/block--system-branding-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 4);
        static $filters = array("escape" => 6, "t" => 6);
        static $functions = array("path" => 5);

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 't'],
                ['path']
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

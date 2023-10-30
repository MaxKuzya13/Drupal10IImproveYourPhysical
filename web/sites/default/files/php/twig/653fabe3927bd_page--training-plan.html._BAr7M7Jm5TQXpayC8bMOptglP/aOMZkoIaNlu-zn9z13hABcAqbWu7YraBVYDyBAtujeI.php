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

/* themes/custom/kenny/templates/page/page--training-plan.html.twig */
class __TwigTemplate_cb10a0732b24384fd2710e1143979500 extends Template
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
        $context["classes"] = "app";
        // line 2
        echo "

<div class=\"";
        // line 4
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 4, $this->source), "html", null, true);
        echo "\">
    <div class=\"";
        // line 5
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 5, $this->source), "html", null, true);
        echo "__container\">
        <header class=\"";
        // line 6
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 6, $this->source), "html", null, true);
        echo "__header\">
            ";
        // line 7
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
        echo "
        </header>


        <main ";
        // line 11
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 11, $this->source) . "__content")], "method", false, false, true, 11), 11, $this->source), "html", null, true);
        echo " >
            <div class=\"main-layout__inner\">
              <div class=\"training-plan__bodypart\">
                <button class=\"filter-button\" data-filter=\"all\">All</button>
                <button class=\"filter-button\" data-filter=\"biceps\">Biceps</button>
                <button class=\"filter-button\" data-filter=\"chest\">Chest</button>
                <button class=\"filter-button\" data-filter=\"shoulders\">Shoulders</button>
                <button class=\"filter-button\" data-filter=\"legs\">Legs</button>
                <button class=\"filter-button\" data-filter=\"triceps\">Triceps</button>
                <button class=\"filter-button\" data-filter=\"back\">Back</button>

              </div>

              <div class=\"content-container\">
                ";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
        echo "
              </div>

            </div>
        </main>

        <footer class=\"";
        // line 31
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 31, $this->source), "html", null, true);
        echo "__footer\">
            ";
        // line 32
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
        echo "
        </footer>
    </div>

</div>";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/page/page--training-plan.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  94 => 32,  90 => 31,  81 => 25,  64 => 11,  57 => 7,  53 => 6,  49 => 5,  45 => 4,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% set classes = 'app' %}


<div class=\"{{ classes }}\">
    <div class=\"{{ classes }}__container\">
        <header class=\"{{ classes }}__header\">
            {{ page.header }}
        </header>


        <main {{ attributes.addClass(classes ~ '__content') }} >
            <div class=\"main-layout__inner\">
              <div class=\"training-plan__bodypart\">
                <button class=\"filter-button\" data-filter=\"all\">All</button>
                <button class=\"filter-button\" data-filter=\"biceps\">Biceps</button>
                <button class=\"filter-button\" data-filter=\"chest\">Chest</button>
                <button class=\"filter-button\" data-filter=\"shoulders\">Shoulders</button>
                <button class=\"filter-button\" data-filter=\"legs\">Legs</button>
                <button class=\"filter-button\" data-filter=\"triceps\">Triceps</button>
                <button class=\"filter-button\" data-filter=\"back\">Back</button>

              </div>

              <div class=\"content-container\">
                {{ page.content }}
              </div>

            </div>
        </main>

        <footer class=\"{{ classes }}__footer\">
            {{ page.footer }}
        </footer>
    </div>

</div>{# /.layout-container #}
", "themes/custom/kenny/templates/page/page--training-plan.html.twig", "/var/www/html/web/themes/custom/kenny/templates/page/page--training-plan.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1);
        static $filters = array("escape" => 4);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set'],
                ['escape'],
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

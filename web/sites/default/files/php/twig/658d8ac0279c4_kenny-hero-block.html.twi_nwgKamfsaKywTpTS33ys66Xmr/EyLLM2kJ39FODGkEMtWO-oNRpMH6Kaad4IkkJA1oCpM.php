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

/* modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig */
class __TwigTemplate_79fd0dab45e8a7c523f7ef6b5d0a22eb extends Template
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
        $context["bem_block"] = "kenny-hero-block";
        // line 2
        $context["classes"] = [0 =>         // line 3
($context["bem_block"] ?? null), 1 => (((        // line 4
($context["image"] ?? null) && ($context["video"] ?? null))) ? (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 4, $this->source) . "--image-and-video")) : ("")), 2 => (((        // line 5
($context["image"] ?? null) &&  !($context["video"] ?? null))) ? (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 5, $this->source) . "--image")) : ("")), 3 => (( !        // line 6
($context["image"] ?? null)) ? (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 6, $this->source) . "--plain")) : ("")), 4 => ((        // line 7
($context["subtitle"] ?? null)) ? (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 7, $this->source) . "--subtitle")) : (($this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 7, $this->source) . "--no-subtitle")))];
        // line 9
        echo "
";
        // line 10
        if ((($context["image"] ?? null) && ($context["video"] ?? null))) {
            // line 11
            echo "  <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 11), 11, $this->source), "html", null, true);
            echo ">
    <video autoplay loop muted class=\"";
            // line 12
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 12, $this->source), "html", null, true);
            echo "__video\">
      ";
            // line 13
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["video"] ?? null));
            foreach ($context['_seq'] as $context["type"] => $context["video_uri"]) {
                // line 14
                echo "        <source src=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getFileUrl($this->sandbox->ensureToStringAllowed($context["video_uri"], 14, $this->source)), "html", null, true);
                echo "\" type=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["type"], 14, $this->source), "html", null, true);
                echo "\">
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['type'], $context['video_uri'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Your browser does not support the video tag"));
            echo "
    </video>
  </div>
";
        } elseif (        // line 19
($context["image"] ?? null)) {
            // line 20
            echo "  <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 20), 20, $this->source), "html", null, true);
            echo ">
    <img src=\"";
            // line 21
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::imageStyleFilter($this->sandbox->ensureToStringAllowed(($context["image"] ?? null), 21, $this->source), "kenny_hero_block_large"), "html", null, true);
            echo "\" alt=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 21, $this->source), "html", null, true);
            echo "\" class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 21, $this->source), "html", null, true);
            echo "__image\">
  </div>
";
        }
        // line 24
        echo "

<div class=\"";
        // line 26
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 26, $this->source), "html", null, true);
        echo "__content\">
  ";
        // line 27
        if (($context["title"] ?? null)) {
            // line 28
            echo "    <h1 class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 28, $this->source), "html", null, true);
            echo "__title\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 28, $this->source), "html", null, true);
            echo "</h1>
  ";
        }
        // line 30
        echo "
  ";
        // line 31
        if (($context["subtitle"] ?? null)) {
            // line 32
            echo "    <div class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 32, $this->source), "html", null, true);
            echo "__subtitle\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["subtitle"] ?? null), 32, $this->source));
            echo "</div>
  ";
        }
        // line 34
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  132 => 34,  124 => 32,  122 => 31,  119 => 30,  111 => 28,  109 => 27,  105 => 26,  101 => 24,  91 => 21,  86 => 20,  84 => 19,  77 => 16,  66 => 14,  62 => 13,  58 => 12,  53 => 11,  51 => 10,  48 => 9,  46 => 7,  45 => 6,  44 => 5,  43 => 4,  42 => 3,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig", "/var/www/html/web/modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1, "if" => 10, "for" => 13);
        static $filters = array("escape" => 11, "t" => 16, "image_style" => 21, "raw" => 32);
        static $functions = array("file_url" => 14);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['escape', 't', 'image_style', 'raw'],
                ['file_url']
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

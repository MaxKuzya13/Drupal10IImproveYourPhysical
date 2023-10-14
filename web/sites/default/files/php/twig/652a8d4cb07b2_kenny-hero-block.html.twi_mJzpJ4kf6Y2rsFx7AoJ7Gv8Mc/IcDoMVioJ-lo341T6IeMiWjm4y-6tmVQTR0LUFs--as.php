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
class __TwigTemplate_eb97f22ba54e49bbdd439a528e501a14 extends Template
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
        echo "<div ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 9), 9, $this->source), "html", null, true);
        echo ">
  ";
        // line 10
        if ((($context["image"] ?? null) && ($context["video"] ?? null))) {
            // line 11
            echo "    <video poster=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::imageStyleFilter($this->sandbox->ensureToStringAllowed(($context["image"] ?? null), 11, $this->source), "thumbnail"), "html", null, true);
            echo "\" autoplay loop muted class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 11, $this->source), "html", null, true);
            echo "__video\">
      ";
            // line 12
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["video"] ?? null));
            foreach ($context['_seq'] as $context["type"] => $context["video_uri"]) {
                // line 13
                echo "        <source src=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getFileUrl($this->sandbox->ensureToStringAllowed($context["video_uri"], 13, $this->source)), "html", null, true);
                echo "\" type=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["type"], 13, $this->source), "html", null, true);
                echo "\">
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['type'], $context['video_uri'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Your browser does not support the video tag"));
            echo "
    </video>

  ";
        } elseif (        // line 18
($context["image"] ?? null)) {
            // line 19
            echo "
    <img src=\"";
            // line 20
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::imageStyleFilter($this->sandbox->ensureToStringAllowed(($context["image"] ?? null), 20, $this->source), "thumbnail"), "html", null, true);
            echo "\" alt=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 20, $this->source), "html", null, true);
            echo "\" class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 20, $this->source), "html", null, true);
            echo "__image\">

  ";
        }
        // line 23
        echo "  <div class=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 23, $this->source), "html", null, true);
        echo "__content\">
    <h1 class=\"";
        // line 24
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 24, $this->source), "html", null, true);
        echo "__title\">";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 24, $this->source), "html", null, true);
        echo "</h1>

    ";
        // line 26
        if (($context["subtitle"] ?? null)) {
            // line 27
            echo "      <div class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["bem_block"] ?? null), 27, $this->source), "html", null, true);
            echo "__subtitle\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["subtitle"] ?? null), 27, $this->source));
            echo "</div>
    ";
        }
        // line 29
        echo "  </div>

</div>
";
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
        return array (  121 => 29,  113 => 27,  111 => 26,  104 => 24,  99 => 23,  89 => 20,  86 => 19,  84 => 18,  77 => 15,  66 => 13,  62 => 12,  55 => 11,  53 => 10,  48 => 9,  46 => 7,  45 => 6,  44 => 5,  43 => 4,  42 => 3,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% set bem_block = 'kenny-hero-block' %}
{% set classes = [
  bem_block,
  image and video ? bem_block ~ '--image-and-video',
  image and not video ? bem_block ~ '--image',
  not image ? bem_block ~ '--plain',
  subtitle ? bem_block ~ '--subtitle' : bem_block ~ '--no-subtitle',
] %}
<div {{ attributes.addClass(classes) }}>
  {% if image and video %}
    <video poster=\"{{ image|image_style('thumbnail') }}\" autoplay loop muted class=\"{{ bem_block }}__video\">
      {% for type, video_uri in video %}
        <source src=\"{{ file_url(video_uri) }}\" type=\"{{ type }}\">
      {% endfor %}
      {{ 'Your browser does not support the video tag'|t }}
    </video>

  {% elseif image %}

    <img src=\"{{ image|image_style('thumbnail') }}\" alt=\"{{ title }}\" class=\"{{ bem_block }}__image\">

  {% endif %}
  <div class=\"{{ bem_block }}__content\">
    <h1 class=\"{{ bem_block }}__title\">{{ title }}</h1>

    {% if subtitle %}
      <div class=\"{{ bem_block }}__subtitle\">{{ subtitle|raw }}</div>
    {% endif %}
  </div>

</div>
", "modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig", "/var/www/html/web/modules/custom/kenny_hero_block/templates/kenny-hero-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1, "if" => 10, "for" => 12);
        static $filters = array("escape" => 9, "image_style" => 11, "t" => 15, "raw" => 27);
        static $functions = array("file_url" => 13);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['escape', 'image_style', 't', 'raw'],
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

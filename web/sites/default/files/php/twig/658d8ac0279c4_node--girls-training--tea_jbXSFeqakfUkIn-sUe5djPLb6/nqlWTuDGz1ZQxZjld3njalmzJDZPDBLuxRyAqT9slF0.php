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

/* themes/custom/kenny/templates/content/node--girls-training--teaser.html.twig */
class __TwigTemplate_7ff7de6d647f221859a10d96d172e503 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'node' => [$this, 'block_node'],
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 70
        echo "
";
        // line 72
        $context["classes"] = [0 => "node", 1 => (("node-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source,         // line 74
($context["node"] ?? null), "bundle", [], "any", false, false, true, 74), 74, $this->source))) . \Drupal\Component\Utility\Html::getClass((((($context["view_mode"] ?? null) != "default")) ? (("-" . $this->sandbox->ensureToStringAllowed(($context["view_mode"] ?? null), 74, $this->source))) : ("")))), 2 => ((twig_get_attribute($this->env, $this->source,         // line 75
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 75)) ? ("node--promoted") : ("")), 3 => ((twig_get_attribute($this->env, $this->source,         // line 76
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 76)) ? ("node--sticky") : ("")), 4 => (( !twig_get_attribute($this->env, $this->source,         // line 77
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 77)) ? ("node--unpublished") : (""))];
        // line 80
        echo "
";
        // line 81
        $context["title_class"] = \Drupal\Component\Utility\Html::getClass((("node-" . $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["node"] ?? null), "bundle", [], "any", false, false, true, 81), 81, $this->source)) . (((($context["view_mode"] ?? null) != "default")) ? (("-" . $this->sandbox->ensureToStringAllowed(($context["view_mode"] ?? null), 81, $this->source))) : (""))));
        // line 82
        echo "
";
        // line 83
        $context["favorite_classes"] = [0 => "favorite-button", 1 => ((        // line 85
($context["is_favorite"] ?? null)) ? ("remove-favorite") : ("add-favorite"))];
        // line 88
        echo "<article";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 88), 88, $this->source), "html", null, true);
        echo ">

  ";
        // line 90
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 90, $this->source), "html", null, true);
        echo "
  ";
        // line 91
        if ((($context["label"] ?? null) &&  !($context["page"] ?? null))) {
            // line 92
            echo "    <h2";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_attributes"] ?? null), 92, $this->source), "html", null, true);
            echo " class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_class"] ?? null), 92, $this->source), "html", null, true);
            echo "__title\">
      <div>";
            // line 93
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 93, $this->source), "html", null, true);
            echo "</div>
    </h2>
  ";
        }
        // line 96
        echo "  ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 96, $this->source), "html", null, true);
        echo "
  ";
        // line 97
        $this->displayBlock('node', $context, $blocks);
        // line 110
        echo "
  <div";
        // line 111
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "node__content"], "method", false, false, true, 111), 111, $this->source), "html", null, true);
        echo ">
    ";
        // line 112
        $this->displayBlock('content', $context, $blocks);
        // line 123
        echo "  </div>


</article>

";
    }

    // line 97
    public function block_node($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 98
        echo "    ";
        if (($context["display_submitted"] ?? null)) {
            // line 99
            echo "
      <footer class=\"node__meta\">
        ";
            // line 101
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_picture"] ?? null), 101, $this->source), "html", null, true);
            echo "
        <div";
            // line 102
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["author_attributes"] ?? null), "addClass", [0 => "node__submitted"], "method", false, false, true, 102), 102, $this->source), "html", null, true);
            echo ">
          ";
            // line 103
            echo t("Submitted by @author_name on @date", array("@author_name" => ($context["author_name"] ?? null), "@date" => ($context["date"] ?? null), ));
            // line 104
            echo "          ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["metadata"] ?? null), 104, $this->source), "html", null, true);
            echo "
        </div>
      </footer>

    ";
        }
        // line 109
        echo "  ";
    }

    // line 112
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 113
        echo "      ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 113, $this->source), "girls_total_weight"), "html", null, true);
        echo "

      <div class=\"node__content-favorite\">
        ";
        // line 116
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "girls_total_weight", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
        echo "
        <button";
        // line 117
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => ($context["favorite_classes"] ?? null)], "method", false, false, true, 117), 117, $this->source), "html", null, true);
        echo " data-uid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_id"] ?? null), 117, $this->source), "html", null, true);
        echo "\" data-nid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["node_id"] ?? null), 117, $this->source), "html", null, true);
        echo "\">
        ";
        // line 118
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["is_favorite"] ?? null)) ? ("Remove from favorite") : ("Add to favorite")));
        echo "
        </button>

      </div>
    ";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/content/node--girls-training--teaser.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 118,  160 => 117,  156 => 116,  149 => 113,  145 => 112,  141 => 109,  132 => 104,  130 => 103,  126 => 102,  122 => 101,  118 => 99,  115 => 98,  111 => 97,  102 => 123,  100 => 112,  96 => 111,  93 => 110,  91 => 97,  86 => 96,  80 => 93,  73 => 92,  71 => 91,  67 => 90,  61 => 88,  59 => 85,  58 => 83,  55 => 82,  53 => 81,  50 => 80,  48 => 77,  47 => 76,  46 => 75,  45 => 74,  44 => 72,  41 => 70,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/kenny/templates/content/node--girls-training--teaser.html.twig", "/var/www/html/web/themes/custom/kenny/templates/content/node--girls-training--teaser.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 72, "if" => 91, "block" => 97, "trans" => 103);
        static $filters = array("clean_class" => 74, "escape" => 88, "without" => 113);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'block', 'trans'],
                ['clean_class', 'escape', 'without'],
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

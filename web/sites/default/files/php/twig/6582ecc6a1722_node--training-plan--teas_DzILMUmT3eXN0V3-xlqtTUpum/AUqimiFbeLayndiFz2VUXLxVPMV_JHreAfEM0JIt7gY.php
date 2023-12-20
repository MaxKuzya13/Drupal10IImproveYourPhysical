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

/* themes/custom/kenny/templates/content/node--training-plan--teaser.html.twig */
class __TwigTemplate_6a09fc80febd5d77581c18d017fdf3bd extends Template
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
        // line 124
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
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 113, $this->source), "total_weight"), "html", null, true);
        echo "

      <div class=\"node__content-favorite\">
        ";
        // line 116
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "total_weight", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
        echo "

        <button";
        // line 118
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => ($context["favorite_classes"] ?? null)], "method", false, false, true, 118), 118, $this->source), "html", null, true);
        echo " data-uid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_id"] ?? null), 118, $this->source), "html", null, true);
        echo "\" data-nid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["node_id"] ?? null), 118, $this->source), "html", null, true);
        echo "\">
        ";
        // line 119
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["is_favorite"] ?? null)) ? ("Remove from favorite") : ("Add to favorite")));
        echo "
        </button>

      </div>
    ";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/content/node--training-plan--teaser.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 119,  160 => 118,  155 => 116,  148 => 113,  144 => 112,  140 => 109,  131 => 104,  129 => 103,  125 => 102,  121 => 101,  117 => 99,  114 => 98,  110 => 97,  102 => 124,  100 => 112,  96 => 111,  93 => 110,  91 => 97,  86 => 96,  80 => 93,  73 => 92,  71 => 91,  67 => 90,  61 => 88,  59 => 85,  58 => 83,  55 => 82,  53 => 81,  50 => 80,  48 => 77,  47 => 76,  46 => 75,  45 => 74,  44 => 72,  41 => 70,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override to display a node.
 *
 * Available variables:
 * - node: The node entity with limited access to object properties and methods.
 *   Only method names starting with \"get\", \"has\", or \"is\" and a few common
 *   methods such as \"id\", \"label\", and \"bundle\" are available. For example:
 *   - node.getCreatedTime() will return the node creation timestamp.
 *   - node.hasField('field_example') returns TRUE if the node bundle includes
 *     field_example. (This does not indicate the presence of a value in this
 *     field.)
 *   - node.isPublished() will return whether the node is published or not.
 *   Calling other methods, such as node.delete(), will result in an exception.
 *   See \\Drupal\\node\\Entity\\Node for a full list of public properties and
 *   methods for the node object.
 * - label: (optional) The title of the node.
 * - content: All node items. Use {{ content }} to print them all,
 *   or print a subset such as {{ content.field_example }}. Use
 *   {{ content|without('field_example') }} to temporarily suppress the printing
 *   of a given child element.
 * - author_picture: The node author user entity, rendered using the \"compact\"
 *   view mode.
 * - metadata: Metadata for this node.
 * - date: (optional) Themed creation date field.
 * - author_name: (optional) Themed author name field.
 * - url: Direct URL of the current node.
 * - display_submitted: Whether submission information should be displayed.
 * - attributes: HTML attributes for the containing element.
 *   The attributes.class element may contain one or more of the following
 *   classes:
 *   - node: The current template type (also known as a \"theming hook\").
 *   - node--type-[type]: The current node type. For example, if the node is an
 *     \"Article\" it would result in \"node--type-article\". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node--view-mode-[view_mode]: The View Mode of the node; for example, a
 *     teaser would result in: \"node--view-mode-teaser\", and
 *     full: \"node--view-mode-full\".
 *   The following are controlled through the node publishing options.
 *   - node--promoted: Appears on nodes promoted to the front page.
 *   - node--sticky: Appears on nodes ordered above other non-sticky nodes in
 *     teaser listings.
 *   - node--unpublished: Appears on unpublished nodes visible only to site
 *     admins.
 * - title_attributes: Same as attributes, except applied to the main title
 *   tag that appears in the template.
 * - content_attributes: Same as attributes, except applied to the main
 *   content tag that appears in the template.
 * - author_attributes: Same as attributes, except applied to the author of
 *   the node tag that appears in the template.
 * - title_prefix: Additional output populated by modules, intended to be
 *   displayed in front of the main title tag that appears in the template.
 * - title_suffix: Additional output populated by modules, intended to be
 *   displayed after the main title tag that appears in the template.
 * - view_mode: View mode; for example, \"teaser\" or \"full\".
 * - teaser: Flag for the teaser state. Will be true if view_mode is 'teaser'.
 * - page: Flag for the full page state. Will be true if view_mode is 'full'.
 * - readmore: Flag for more state. Will be true if the teaser content of the
 *   node cannot hold the main body content.
 * - logged_in: Flag for authenticated user status. Will be true when the
 *   current user is a logged-in member.
 * - is_admin: Flag for admin user status. Will be true when the current user
 *   is an administrator.
 *
 * @see template_preprocess_node()
 *
 */
#}

{%
  set classes = [
  'node',
  'node-' ~ node.bundle|clean_class ~ (view_mode != 'default' ? '-' ~ view_mode)|clean_class,
  node.isPromoted() ? 'node--promoted',
  node.isSticky() ? 'node--sticky',
  not node.isPublished() ? 'node--unpublished'
]
%}

{% set title_class =  ('node-' ~ node.bundle ~ (view_mode != 'default' ? '-' ~ view_mode))|clean_class %}

{% set favorite_classes = [
  'favorite-button',
  is_favorite ? 'remove-favorite' : 'add-favorite'
  ]
%}
<article{{ attributes.addClass(classes) }}>

  {{ title_prefix }}
  {% if label and not page %}
    <h2{{ title_attributes }} class=\"{{ title_class }}__title\">
      <div>{{ label }}</div>
    </h2>
  {% endif %}
  {{ title_suffix }}
  {% block node %}
    {% if display_submitted %}

      <footer class=\"node__meta\">
        {{ author_picture }}
        <div{{ author_attributes.addClass('node__submitted') }}>
          {% trans %}Submitted by {{ author_name }} on {{ date }}{% endtrans %}
          {{ metadata }}
        </div>
      </footer>

    {% endif %}
  {% endblock %}

  <div{{ content_attributes.addClass('node__content') }}>
    {% block content %}
      {{ content|without('total_weight') }}

      <div class=\"node__content-favorite\">
        {{ content.total_weight}}

        <button{{ content_attributes.addClass(favorite_classes) }} data-uid=\"{{ user_id }}\" data-nid=\"{{ node_id }}\">
        {{ is_favorite ? 'Remove from favorite' : \"Add to favorite\" }}
        </button>

      </div>
    {% endblock %}
  </div>


</article>
", "themes/custom/kenny/templates/content/node--training-plan--teaser.html.twig", "/var/www/html/web/themes/custom/kenny/templates/content/node--training-plan--teaser.html.twig");
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

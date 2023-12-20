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

/* themes/custom/kenny/templates/content/node--girls-training--full.html.twig */
class __TwigTemplate_0fe55c45b0dbad83a26e58660f3a88de extends Template
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
        echo "
<div class=\"training__container\">

  <article";
        // line 91
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 91), 91, $this->source), "html", null, true);
        echo ">

    ";
        // line 93
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 93, $this->source), "html", null, true);
        echo "
    ";
        // line 94
        if ((($context["label"] ?? null) &&  !($context["page"] ?? null))) {
            // line 95
            echo "      <h2";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_attributes"] ?? null), 95, $this->source), "html", null, true);
            echo " class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_class"] ?? null), 95, $this->source), "html", null, true);
            echo "__title\">
        <a href=\"";
            // line 96
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 96, $this->source), "html", null, true);
            echo "\" rel=\"bookmark\" class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_class"] ?? null), 96, $this->source), "html", null, true);
            echo "__link\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 96, $this->source), "html", null, true);
            echo "</a>
      </h2>
    ";
        }
        // line 99
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 99, $this->source), "html", null, true);
        echo "
    ";
        // line 100
        $this->displayBlock('node', $context, $blocks);
        // line 113
        echo "
    <div";
        // line 114
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "node__content"], "method", false, false, true, 114), 114, $this->source), "html", null, true);
        echo ">
      ";
        // line 115
        $this->displayBlock('content', $context, $blocks);
        // line 126
        echo "    </div>


  </article>
</div>
";
    }

    // line 100
    public function block_node($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 101
        echo "      ";
        if (($context["display_submitted"] ?? null)) {
            // line 102
            echo "
        <footer class=\"node__meta\">
          ";
            // line 104
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_picture"] ?? null), 104, $this->source), "html", null, true);
            echo "
          <div";
            // line 105
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["author_attributes"] ?? null), "addClass", [0 => "node__submitted"], "method", false, false, true, 105), 105, $this->source), "html", null, true);
            echo ">
            ";
            // line 106
            echo t("Submitted by @author_name on @date", array("@author_name" => ($context["author_name"] ?? null), "@date" => ($context["date"] ?? null), ));
            // line 107
            echo "            ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["metadata"] ?? null), 107, $this->source), "html", null, true);
            echo "
          </div>
        </footer>

      ";
        }
        // line 112
        echo "    ";
    }

    // line 115
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 116
        echo "        ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 116, $this->source), "girls_total_weight"), "html", null, true);
        echo "

        <div class=\"node__content-favorite\">
          ";
        // line 119
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "girls_total_weight", [], "any", false, false, true, 119), 119, $this->source), "html", null, true);
        echo "
          <button";
        // line 120
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => ($context["favorite_classes"] ?? null)], "method", false, false, true, 120), 120, $this->source), "html", null, true);
        echo " data-uid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_id"] ?? null), 120, $this->source), "html", null, true);
        echo "\" data-nid=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["node_id"] ?? null), 120, $this->source), "html", null, true);
        echo "\">
          ";
        // line 121
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["is_favorite"] ?? null)) ? ("Remove from favorite") : ("Add to favorite")));
        echo "
          </button>

        </div>
      ";
    }

    public function getTemplateName()
    {
        return "themes/custom/kenny/templates/content/node--girls-training--full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 121,  168 => 120,  164 => 119,  157 => 116,  153 => 115,  149 => 112,  140 => 107,  138 => 106,  134 => 105,  130 => 104,  126 => 102,  123 => 101,  119 => 100,  110 => 126,  108 => 115,  104 => 114,  101 => 113,  99 => 100,  94 => 99,  84 => 96,  77 => 95,  75 => 94,  71 => 93,  66 => 91,  61 => 88,  59 => 85,  58 => 83,  55 => 82,  53 => 81,  50 => 80,  48 => 77,  47 => 76,  46 => 75,  45 => 74,  44 => 72,  41 => 70,);
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

<div class=\"training__container\">

  <article{{ attributes.addClass(classes) }}>

    {{ title_prefix }}
    {% if label and not page %}
      <h2{{ title_attributes }} class=\"{{ title_class }}__title\">
        <a href=\"{{ url }}\" rel=\"bookmark\" class=\"{{ title_class }}__link\">{{ label }}</a>
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
        {{ content|without('girls_total_weight') }}

        <div class=\"node__content-favorite\">
          {{ content.girls_total_weight}}
          <button{{ content_attributes.addClass(favorite_classes) }} data-uid=\"{{ user_id }}\" data-nid=\"{{ node_id }}\">
          {{ is_favorite ? 'Remove from favorite' : \"Add to favorite\" }}
          </button>

        </div>
      {% endblock %}
    </div>


  </article>
</div>
", "themes/custom/kenny/templates/content/node--girls-training--full.html.twig", "/var/www/html/web/themes/custom/kenny/templates/content/node--girls-training--full.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 72, "if" => 94, "block" => 100, "trans" => 106);
        static $filters = array("clean_class" => 74, "escape" => 91, "without" => 116);
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

<?php

/* home.twig */
class __TwigTemplate_43760d1ffe38203fbf0622265dc12baa2ee2fa39e6b228a85d3f263aaa910ff4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "home.twig", 1);
        $this->blocks = array(
            'page_title' => array($this, 'block_page_title'),
            'body_header' => array($this, 'block_body_header'),
            'body_core' => array($this, 'block_body_core'),
            'body_footer' => array($this, 'block_body_footer'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page_title($context, array $blocks = array())
    {
        echo " My Webpage ";
    }

    // line 5
    public function block_body_header($context, array $blocks = array())
    {
        // line 6
        echo "<h1>My Webpage</h1>
";
        // line 7
        echo twig_escape_filter($this->env, ($context["a_variable"] ?? null), "html", null, true);
        echo "

<ul id=\"navigation\">
  ";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["navigation"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 11
            echo "  <li><a href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "href", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "caption", array()), "html", null, true);
            echo "</a></li>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "</ul>
";
    }

    // line 16
    public function block_body_core($context, array $blocks = array())
    {
        // line 17
        echo "<h1>UPDATE</h1>
  ";
        // line 18
        if (($context["name"] ?? null)) {
            // line 19
            echo "    <h2>Hello home.twig ";
            echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
            echo "</h2>
  ";
        }
        // line 21
        echo "

    ";
        // line 23
        if (($context["races"] ?? null)) {
            // line 24
            echo "    <h3>In Races</h3>
    ";
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["races"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["race"]) {
                // line 26
                echo "
      <h2>Races: ";
                // line 27
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["race"], "id", array()), "html", null, true);
                echo "</h2>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['race'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "    ";
        } else {
            // line 30
            echo "      <h3>Races: Nothing</h3>
      ";
        }
        // line 32
        echo "
  <h2>Race: ";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["race"] ?? null), "name", array()), "html", null, true);
        echo "</h2>
  ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["race"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 35
            echo "    <h3>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), $context["item"], "name", array()), "html", null, true);
            echo "</h3>
  ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 37
            echo "    <h3>Race: Nothing</h3>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "
    <p>Try <a href=\"http://www.slimframework.com\">SlimFramework</a></p>
";
    }

    // line 43
    public function block_body_footer($context, array $blocks = array())
    {
        echo " ";
    }

    public function getTemplateName()
    {
        return "home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 43,  141 => 39,  134 => 37,  126 => 35,  121 => 34,  117 => 33,  114 => 32,  110 => 30,  107 => 29,  99 => 27,  96 => 26,  92 => 25,  89 => 24,  87 => 23,  83 => 21,  77 => 19,  75 => 18,  72 => 17,  69 => 16,  64 => 13,  53 => 11,  49 => 10,  43 => 7,  40 => 6,  37 => 5,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home.twig", "/home/hyenaquenn/slim_framework/templates/home.twig");
    }
}

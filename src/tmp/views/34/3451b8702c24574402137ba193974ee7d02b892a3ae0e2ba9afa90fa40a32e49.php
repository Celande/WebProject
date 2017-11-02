<?php

/* layout.html */
class __TwigTemplate_060c2b5d81bbb578de76d155af652584832d51a095966ac0180d6c43f9c4554d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'page_title' => array($this, 'block_page_title'),
            'body_header' => array($this, 'block_body_header'),
            'body_core' => array($this, 'block_body_core'),
            'body_footer' => array($this, 'block_body_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\"/>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"bootstrap/css/bootstrap.css\">
        <script src=\"js/bootstrap.bundle.js\"></script>

        <title>";
        // line 9
        $this->displayBlock('page_title', $context, $blocks);
        echo "</title>
    </head>
    <body>
        <div id=\"header\">";
        // line 12
        $this->displayBlock('body_header', $context, $blocks);
        echo "</div>
        <div id=\"core\">";
        // line 13
        $this->displayBlock('body_core', $context, $blocks);
        echo "</div>
        <div id=\"footer\">";
        // line 14
        $this->displayBlock('body_footer', $context, $blocks);
        echo "</div>
    </body>
</html>
";
    }

    // line 9
    public function block_page_title($context, array $blocks = array())
    {
        echo " ";
    }

    // line 12
    public function block_body_header($context, array $blocks = array())
    {
        echo " ";
    }

    // line 13
    public function block_body_core($context, array $blocks = array())
    {
        echo " ";
    }

    // line 14
    public function block_body_footer($context, array $blocks = array())
    {
        echo " ";
    }

    public function getTemplateName()
    {
        return "layout.html";
    }

    public function getDebugInfo()
    {
        return array (  73 => 14,  67 => 13,  61 => 12,  55 => 9,  47 => 14,  43 => 13,  39 => 12,  33 => 9,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.html", "/home/hyenaquenn/slim_framework/templates/layout.html");
    }
}

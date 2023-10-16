<?php

namespace Learn\View;

/**
 * Class responsible for rendering views in an application.
 */
class LearnEngine implements View
{
    protected string $viewsDirectory;
    protected string $defaultLayout = "main";
    protected string $contentAnnotation = "@content";

    /**
     * Constructor to initialize the ViewRenderer with a views directory.
     *
     * @param string $viewsDirectory The directory where view files are located.
     */
    public function __construct(string $viewsDirectory)
    {
        $this->viewsDirectory = $viewsDirectory;
    }

    /**
     * Render a view with optional parameters and a layout.
     *
     * @param string $view   The name of the view file to render.
     * @param array $params  An associative array of parameters to pass to the view.
     * @param string|null $layout The name of the layout file to use, or null to use the default layout.
     * @return string The rendered HTML content.
     */
    public function render(string $view, array $params = [], $layout = null): string
    {
        // Render the layout and view content.
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);
        $viewContent = $this->renderView($view, $params);

        // Replace the content annotation in the layout with the view content.
        return str_replace($this->contentAnnotation, $viewContent, $layoutContent);
    }

    /**
     * Render a view with optional parameters.
     *
     * @param string $view   The name of the view file to render.
     * @param array $params  An associative array of parameters to pass to the view.
     * @return string The rendered HTML content of the view.
     */
    protected function renderView(string $view, array $params = []): string
    {
        // Render the view content from a PHP file.
        return $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
    }

    /**
     * Render a layout.
     *
     * @param string $layout The name of the layout file to render.
     * @return string The rendered HTML content of the layout.
     */
    protected function renderLayout(string $layout): string
    {
        // Render the layout content from a PHP file.
        return $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
    }

    /**
     * Render the content of a PHP file with optional parameters.
     *
     * @param string $phpFile The path to the PHP file to include and render.
     * @param array $params   An associative array of parameters to pass to the included file.
     * @return string The rendered output of the included PHP file.
     */
    protected function phpFileOutput(string $phpFile, array $params = []): string
    {
        // Extract parameters as variables and capture the output of the included file.
        foreach($params as $param => $value) {
            $$param = $value;
        }
        ob_start();
        include_once $phpFile;
        return ob_get_clean();
    }

}

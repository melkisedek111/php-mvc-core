<?php
namespace app\core;

use app\core\Application;

class View
{
    public string $title = '';
    public function renderView($view, $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent); // look for {{content}}(placeholder) inside $layoutContent and replace it with $viewContent
    }

    protected function layoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }
        ob_start(); // start the ouput cache, nothing is outputed in the browser
        include_once Application::$ROOT_DIR."/views//layouts/$layout.php";
        return ob_get_clean(); // returns the value whatever is it already buffer and clears the buffer
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start(); // start the ouput cache, nothing is outputed in the browser
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean(); // returns the value whatever is it already buffer and clears the buffer
    }
}

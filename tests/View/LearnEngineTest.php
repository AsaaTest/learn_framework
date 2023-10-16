<?php

namespace Learn\Tests\View;

use Learn\View\LearnEngine;
use PHPUnit\Framework\TestCase;

/**
 * Test class for the LearnEngine view rendering.
 */
class LearnEngineTest extends TestCase
{
    /**
     * Test rendering a template with parameters.
     */
    public function test_renders_template_with_parameters()
    {
        // Define test parameters.
        $parameter1 = "test1";
        $parameter2 = 1997;

        // Define the expected output HTML with placeholders for parameters.
        $expected = "<html>
        <body>
        <h1>$parameter1</h1>
        <h1>$parameter2</h1>
        </body>
        </html>";

        // Create a LearnEngine instance with the views directory.
        $engine = new LearnEngine(__DIR__. "/views");

        // Render the 'test' template with the provided parameters and 'layout'.
        $content = $engine->render('test', compact('parameter1', 'parameter2'), 'layout');

        // Remove whitespace and compare the expected and actual content.
        $this->assertEquals(preg_replace("/\s*/", "", $expected), preg_replace("/\s*/", "", $content));
    }
}

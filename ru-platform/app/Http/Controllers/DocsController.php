<?php

namespace App\Http\Controllers;

class DocsController extends Controller
{
    public function apiYaml()
    {
        $candidates = [
            base_path('docs/api.yaml'),
            base_path('../docs/api.yaml'),
            base_path('..\\docs\\api.yaml'),
            base_path('docs/api.yml'),
            base_path('resources/docs/api.yaml'),
        ];

        $path = null;
        foreach ($candidates as $p) {
            if (file_exists($p)) {
                $path = $p;
                break;
            }
        }

        if (! $path) {
            abort(404, 'API docs not found');
        }

        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'application/x-yaml');
    }

    public function ui()
    {
        // Simple Swagger UI page using CDN and loading the API YAML from the docs route
        $yamlUrl = url('/api/docs/api.yaml');
        $html = <<<HTML
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>RU Platform API Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4/swagger-ui.css" />
  </head>
  <body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script>
      const ui = SwaggerUIBundle({
        url: '$yamlUrl',
        dom_id: '#swagger-ui',
        deepLinking: true,
      });
    </script>
  </body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }
}
